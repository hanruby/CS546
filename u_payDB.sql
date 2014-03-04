CREATE DATABASE u_pay;

USE u_pay;

CREATE TABLE taxRate(id INT NOT NULL AUTO_INCREMENT, minSalary DECIMAL(9,2) UNSIGNED NOT NULL, taxRate DECIMAL(2,2) UNSIGNED NOT NULL, PRIMARY KEY (id));

CREATE TABLE rank(id INT NOT NULL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, baseSalary DECIMAL(9,2) UNSIGNED NOT NULL, employeeType ENUM('Administrator', 'Manager', 'Software Developer') NOT NULL, PRIMARY KEY (id), UNIQUE KEY (name));

CREATE TABLE employee(id INT NOT NULL AUTO_INCREMENT, activeFlag BIT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(12) NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(75) NOT NULL, rank INT NOT NULL, taxId VARCHAR(9) NOT NULL, numDeductions INT NOT NULL, salary DECIMAL(9,2) UNSIGNED NOT NULL, FOREIGN KEY (rank) REFERENCES rank(id), PRIMARY KEY (id));

CREATE TABLE department(id INT NOT NULL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, UNIQUE KEY (name), PRIMARY KEY (id));

CREATE TABLE paystub(id INT NOT NULL AUTO_INCREMENT, employee INT NOT NULL, name VARCHAR(50) NOT NULL, rank INT NOT NULL, department INT NOT NULL, salary DECIMAL(9,2) UNSIGNED NOT NULL, deductions INT NOT NULL, taxWithheld DECIMAL(9,2) UNSIGNED NOT NULL, FOREIGN KEY (rank) REFERENCES rank(id), FOREIGN KEY (department) REFERENCES department(id), PRIMARY KEY (id));	

CREATE TABLE loginSession(sessionID VARCHAR(128) NOT NULL, authenticateEmployee INT NOT NULL, PRIMARY KEY(sessionID), FOREIGN KEY(authenticateEmployee) REFERENCES employee(id));

CREATE TABLE employeeDepartmentAssociation(employee INT, department INT, FOREIGN KEY (employee) REFERENCES employee(id), FOREIGN KEY (department) REFERENCES department(id));
