<?php

class ProjectEmployee
    extends GetterSetter
    implements JsonSerializable
{

    private $_id;
    private $_startDate;
    private $_endDate;
    private $_lastPayPeriodEndDate;
    private $_project;
    private $_employee;
    private $_department;
    private $_percentAllocation = 0;


    /**
     * Constructs a new ProjectEmployee object.
     *
     * @param   int           $id           The unique entry ID generated by the database.
     * @param   DateTime      $startDate    First date that this entry is effective.
     * @param   DateTime|null $endDate      Last date (inclusive) that this entry is effective
     * @param   DateTime|null $lastPayPeriodEndDate End date of the last pay period that this entry has been used for.
     * @param   Project       $project
     * @param   Employee      $employee
     * @param   Department    $department
     * @param   double        $percentAllocation
     */
    public function __construct(
            $id, DateTime $startDate, $endDate, $lastPayPeriodEndDate,
            Project $project, Employee $employee, Department $department, $percentAllocation
        )
    {
        if (!is_numeric($id))
            throw new Exception("The \$id parameter must be an integer");
        $this->_id = (int) $id;

        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $this->project = $project;
        $this->employee = $employee;
        $this->department = $department;

        $this->percentAllocation = $percentAllocation;

        // Must be set last
        $this->lastPayPeriodEndDate = $lastPayPeriodEndDate;
    } // __construct

    public function jsonSerialize() {
        $rv = new StdClass();
        $rv->id = $this->id;
        $rv->startDate = $this->startDate->format("Y-m-d");
        $rv->endDate = ($this->endDate ? $this->endDate->format("Y-m-d") : null);
        $rv->lastPayPeriodEndDate = ($this->lastPayPeriodEndDate ? $this->lastPayPeriodEndDate->format("Y-m-d") : null);
        //$rv->project = $this->project;
        $rv->department = $this->department;
        $rv->employee = $this->employee;
        $rv->percentAllocation = $this->percentAllocation;
        return $rv;
    } // jsonSerialize

    protected function getId() {
        return $this->_id;
    } // getId

    protected function getStartDate() {
        return $this->_startDate;
    } // getStartDate

    protected function setStartDate(DateTime $newStartDate) {
        if ($newStartDate == null)
            throw new Exception("The startDate cannot be null");

        if ($this->lastPayPeriodEndDate)
            throw new Exception("The startDate cannot be modified if the lastPayPeriodEndDate is set");

        if ($this->endDate && ($newStartDate > $this->endDate))
            throw new Exception("The startDate cannot be greater than the endDate");

        $this->_startDate = $newStartDate;
    } // setStartDate

    protected function getEndDate() {
        return $this->_endDate;
    } // getEndDate

    protected function setEndDate($newEndDate) {
        if ($newEndDate !== null) {
            if (!($newEndDate instanceof DateTime))
                throw new Exception("Argument 1 passed to ". __METHOD__ ." must be an instance of DateTime, ". (is_object($newEndDate) ? get_class($newEndDate) : gettype($newEndDate)) ." given");

            if ($newEndDate < $this->startDate)
                throw new Exception("The endDate cannot be less than the startDate");

            // The endDate cannot be set to a date before the last pay period end date,
            //  however, the last pay period end date can be set later than the end date...
            if ($this->lastPayPeriodEndDate && ($newEndDate <= $this->lastPayPeriodEndDate))
                throw new Exception("The endDate must be greater than the lastPayPeriodEndDate");
        }

        $this->_endDate = $newEndDate;
    } // setEndDate

    protected function getLastPayPeriodEndDate() {
        return $this->_lastPayPeriodEndDate;
    } // getLastPayPeriodEndDate

    protected function setLastPayPeriodEndDate($newEndDate) {
        if ($newEndDate !== null) {
            if (!($newEndDate instanceof DateTime))
                throw new Exception("Argument 1 passed to ". __METHOD__ ." must be an instance of DateTime, ". (is_object($newEndDate) ? get_class($newEndDate) : gettype($newEndDate)) ." given");

            if ($newEndDate < $this->startDate)
                throw new Exception("The lastPayPeriodEndDate cannot be less than the startDate");
        }

        $this->_lastPayPeriodEndDate = $newEndDate;
    } // setLastPayPeriodEndDate

    protected function getDepartment() {
        return $this->_department;
    } // getDepartment

    protected function setDepartment( Department $newDepartment ) {
        if ($this->lastPayPeriodEndDate)
            throw new Exception("The department cannot be modified if the lastPayPeriodEndDate is set");

        $this->_department = $newDepartment;
    } // setDepartment

    protected function getProject() {
        return $this->_project;
    } // getProject

    protected function setProject( Project $newProject ) {
        if ($this->lastPayPeriodEndDate)
            throw new Exception("The project cannot be modified if the lastPayPeriodEndDate is set");

        $this->_project = $newProject;
    } // setProject

    protected function getEmployee() {
        return $this->_employee;
    } // getEmployee
    
    protected function setEmployee(Employee $newEmployee) {
        if ($this->lastPayPeriodEndDate)
            throw new Exception("The employee cannot be modified if the lastPayPeriodEndDate is set");

        $this->_employee = $newEmployee;
    } // setEmployee

    protected function getPercentAllocation() {
        return $this->_percentAllocation;
    } // getPercentAllocation
    
    protected function setPercentAllocation($newPercentAllocation) {
        if ($this->lastPayPeriodEndDate)
            throw new Exception("The percentAllocation cannot be modified if the lastPayPeriodEndDate is set");

        if (!is_numeric($newPercentAllocation) || ($newPercentAllocation < 0) || ($newPercentAllocation > 100))
            throw new Exception("PercentAllocation must be a number between 0 and 100 inclusive");

        $this->_percentAllocation = (double) $newPercentAllocation;
    } // newPercentAllocation

    protected function getIsActive() {
        $today = (new DateTime())->setTime(0, 0, 0);
        return ($today >= $this->startDate) &&
                (($this->endDate == null) || ($today <= $this->endDate));
    } // getIsActive()

    public function __toString() {
        return __CLASS__ ."(id=$this->id, startDate=$this->startDate, endDate=$this->endDate, lastPayPeriodEndDate=$this->lastPayPeriodEndDate, department=$this->department, employee=$this->employee, percentAllocation=$this->percentAllocation)";
    } // __toString

} // class ProjectEmployee
