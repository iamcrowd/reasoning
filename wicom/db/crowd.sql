-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 24, 2018 at 12:16 AM
-- Server version: 5.6.40
-- PHP Version: 5.6.33-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `crowd`
--

-- --------------------------------------------------------

--
-- Table structure for table `model`
--

CREATE TABLE IF NOT EXISTS `model` (
  `name` char(20) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `owner` char(20) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `json` longtext COLLATE utf8_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `model`
--

INSERT INTO `model` (`name`, `owner`, `json`) VALUES
('call', 'german', '{"classes":[{"name":"Phone","attrs":"[]","methods":"[]","position":{"x":63,"y":58}},{"name":"CellPhone","attrs":"[]","methods":"[]","position":{"x":75,"y":202}},{"name":"Call","attrs":"[]","methods":"[]","position":{"x":542,"y":61}},{"name":"MobileCall","attrs":"[]","methods":"[]","position":{"x":478,"y":220}}],"links":[{"name":"r2","classes":["CellPhone"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Phone","constraint":["disjoint"]},{"name":"r3","classes":["MobileCall"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Call","constraint":["disjoint"]},{"name":"makes","classes":["Phone","Phone"],"multiplicity":["1..1","1..*"],"roles":["r1","r1"],"type":"association"}],"owllink":""}'),
('modelgb', 'german', '{"classes":[{"name":"Class","attrs":"[]","methods":"[]","position":{"x":58,"y":36}},{"name":"A","attrs":"[]","methods":"[]","position":{"x":111,"y":161}}],"links":[{"name":"r1","classes":["A"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Class","constraint":[]}],"owllink":""}'),
('phone', 'german', '{"classes":[{"name":"Phone","attrs":"[]","methods":"[]","position":{"x":684,"y":50}},{"name":"PhoneCall","attrs":"[]","methods":"[]","position":{"x":281,"y":49}},{"name":"MobileCall","attrs":"[]","methods":"[]","position":{"x":247,"y":222}},{"name":"Cell","attrs":"[]","methods":"[]","position":{"x":653,"y":217}},{"name":"FixedPoint","attrs":"[]","methods":"[]","position":{"x":793,"y":221}}],"links":[{"name":"Origin","classes":["PhoneCall","Phone"],"multiplicity":["1..1","1..1"],"roles":["call","from"],"type":"association"},{"name":"Origin","classes":["PhoneCall","Phone"],"multiplicity":["1..1","1..1"],"roles":["call","from"],"type":"association","associated_class":{"name":"Origin","position":{"x":473,"y":120}}},{"name":"r1","classes":["MobileCall"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"PhoneCall","constraint":[]},{"name":"r2","classes":["Cell","FixedPoint"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Phone","constraint":["disjoint","covering"]},{"name":"MOrigin","classes":["MobileCall","Cell"],"multiplicity":["1..1","1..1"],"roles":["call","from"],"type":"association"},{"name":"MOrigin","classes":["MobileCall","Cell"],"multiplicity":["1..1","1..1"],"roles":["call","from"],"type":"association","associated_class":{"name":"MOrigin","position":{"x":419,"y":293}}}],"owllink":""}'),
('Prueba', 'german', '{"classes":[{"name":"Class","attrs":"[]","methods":"(new String(\\"[]\\"))","position":{"x":308,"y":185}},{"name":"Prueba","attrs":"[]","methods":"(new String(\\"[]\\"))","position":{"x":251,"y":67}}],"links":[{"name":"r2","classes":["Class"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Prueba","constraint":[]}],"owllink":"\\n"}'),
('test', 'christian', '{"classes":[{"name":"Class","attrs":"[]","methods":"[]","position":{"x":190,"y":168}},{"name":"test","attrs":"[]","methods":"[]","position":{"x":580,"y":157}}],"links":[{"name":"a","classes":["Class","test"],"multiplicity":["1..*","1..10"],"roles":["r1","r2"],"type":"association"}],"owllink":""}'),
('Thesis 1', 'christian', '{"classes":[{"name":"PhoneBill","attrs":[],"methods":[],"position":{"x":28,"y":29}},{"name":"CellPhone","attrs":[],"methods":[],"position":{"x":803,"y":283}},{"name":"FixedPhone","attrs":[],"methods":[],"position":{"x":1079,"y":285}},{"name":"Phone","attrs":[],"methods":[],"position":{"x":939,"y":27}},{"name":"PhoneCall","attrs":[],"methods":[],"position":{"x":407,"y":27}},{"name":"MobileCall","attrs":[],"methods":[],"position":{"x":405,"y":279}}],"links":[{"name":"r1","classes":["CellPhone","FixedPhone"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Phone","constraint":["disjoint","covering"]},{"name":"r5","classes":["PhoneBill","PhoneCall"],"multiplicity":["1..1","1..*"],"roles":["",""],"type":"association"},{"name":"r2","classes":["MobileCall"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"PhoneCall","constraint":[]},{"name":"r3","classes":["PhoneCall","Phone"],"multiplicity":["..","1..1"],"roles":["",""],"type":"association"},{"name":"r4","classes":["MobileCall","CellPhone"],"multiplicity":["..",".."],"roles":["",""],"type":"association"}]}'),
('Thesis_1', 'christian', '{"classes":[{"name":"PhoneBill","attrs":[],"methods":[],"position":{"x":28,"y":29}},{"name":"CellPhone","attrs":[],"methods":[],"position":{"x":803,"y":283}},{"name":"FixedPhone","attrs":[],"methods":[],"position":{"x":1079,"y":285}},{"name":"Phone","attrs":[],"methods":[],"position":{"x":939,"y":27}},{"name":"PhoneCall","attrs":[],"methods":[],"position":{"x":407,"y":27}},{"name":"MobileCall","attrs":[],"methods":[],"position":{"x":405,"y":279}}],"links":[{"name":"r1","classes":["CellPhone","FixedPhone"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Phone","constraint":["disjoint","covering"]},{"name":"r5","classes":["PhoneBill","PhoneCall"],"multiplicity":["1..1","1..*"],"roles":["",""],"type":"association"},{"name":"r2","classes":["MobileCall"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"PhoneCall","constraint":[]},{"name":"r3","classes":["PhoneCall","Phone"],"multiplicity":["..","1..1"],"roles":["",""],"type":"association"},{"name":"r4","classes":["MobileCall","CellPhone"],"multiplicity":["..",".."],"roles":["",""],"type":"association"}]}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `name` char(20) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `pass` char(20) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `pass`) VALUES
('christian', 'christian'),
('german', 'german');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `model`
--
ALTER TABLE `model`
 ADD PRIMARY KEY (`name`,`owner`), ADD KEY `owner` (`owner`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`name`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model`
--
ALTER TABLE `model`
ADD CONSTRAINT `model_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
