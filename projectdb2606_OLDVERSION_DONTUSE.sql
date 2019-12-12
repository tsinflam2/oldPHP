-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2014 at 08:49 AM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `projectdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `courseschedule`
--

CREATE TABLE IF NOT EXISTS `courseschedule` (
  `csNo` int(5) NOT NULL AUTO_INCREMENT,
  `csDate` date DEFAULT NULL,
  `tcNo` int(5) NOT NULL,
  PRIMARY KEY (`csNo`),
  KEY `fk_CourseSchedule` (`tcNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE IF NOT EXISTS `enrollment` (
  `memNo` int(5) NOT NULL,
  `tcNo` int(5) NOT NULL,
  `status` varchar(1) NOT NULL,
  PRIMARY KEY (`memNo`,`tcNo`),
  KEY `fk_Enrollment_memNo` (`memNo`),
  KEY `fk_Enrollment_tcNo` (`tcNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `enrollment`
--
DROP TRIGGER IF EXISTS `update_available_and_delete_not_accepted`;
DELIMITER //
CREATE TRIGGER `update_available_and_delete_not_accepted` AFTER UPDATE ON `enrollment`
 FOR EACH ROW BEGIN
DECLARE count INT default 0;
DECLARE size INT default 0;
SET count = (Select count(*) from enrollment where tcNo=New.tcNO and status='a');
SET size = (Select tcSize from trainingcourse
where New.tcNo = trainingcourse.tcNo);
IF(count=size) THEN
	UPDATE trainingcourse
    SET available = 'n'
    where tcNO=New.tcNo;
END IF;
	UPDATE enrollment
    SET status='r'
    where tcNO=New.tcNo and New.status!='a';
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `insNo` int(5) NOT NULL AUTO_INCREMENT,
  `insSkill` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`insNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `memNo` int(5) NOT NULL AUTO_INCREMENT,
  `memInterest` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`memNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `msgNo` int(5) NOT NULL AUTO_INCREMENT,
  `msgDateTime` datetime NOT NULL,
  `msgSubject` varchar(50) NOT NULL,
  `msgContent` varchar(200) NOT NULL,
  `msgFrm` int(5) NOT NULL,
  PRIMARY KEY (`msgNo`),
  KEY `fk_Message` (`msgFrm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messagefolder`
--

CREATE TABLE IF NOT EXISTS `messagefolder` (
  `mfNo` int(5) NOT NULL AUTO_INCREMENT,
  `msName` varchar(30) NOT NULL,
  `userNo` int(5) NOT NULL,
  PRIMARY KEY (`mfNo`),
  KEY `fk_MessageFolder` (`userNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `receivedmessage`
--

CREATE TABLE IF NOT EXISTS `receivedmessage` (
  `msgNo` int(5) NOT NULL,
  `msgTo` int(5) NOT NULL,
  `mfNo` int(5) NOT NULL,
  `msgStatus` varchar(10) NOT NULL,
  KEY `fk_ReceivedMessage_msgNo` (`msgNo`),
  KEY `fk_ReceivedMessage_msgTo` (`msgTo`),
  KEY `fk_ReceivedMessage_mfNo` (`mfNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trainingcourse`
--

CREATE TABLE IF NOT EXISTS `trainingcourse` (
  `tcNo` int(5) NOT NULL AUTO_INCREMENT,
  `tcName` varchar(30) NOT NULL,
  `tcSize` int(3) NOT NULL,
  `tcVenue` varchar(255) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `recPattern` varchar(1) DEFAULT NULL,
  `available` varchar(1) NOT NULL DEFAULT 'y',
  `insNo` int(5) DEFAULT NULL,
  PRIMARY KEY (`tcNo`),
  KEY `fk_TrainingCourse` (`insNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userNo` int(5) NOT NULL AUTO_INCREMENT,
  `loginName` varchar(30) NOT NULL,
  `loginPswd` varchar(10) NOT NULL,
  `isLoggedIn` varchar(1) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `userGender` varchar(1) NOT NULL,
  `userPhoto` varchar(30) DEFAULT NULL,
  `memNo` int(5) NOT NULL,
  `insNo` int(5) NOT NULL,
  PRIMARY KEY (`userNo`),
  UNIQUE KEY `loginName` (`loginName`),
  KEY `fk_Users_mem` (`memNo`),
  KEY `fk_Users_int` (`insNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_courseschedule`
--

CREATE TABLE IF NOT EXISTS `user_courseschedule` (
  `userNo` int(5) NOT NULL,
  `csNo` int(5) NOT NULL,
  PRIMARY KEY (`userNo`,`csNo`),
  KEY `fk_User_CourseSchedule_csNo` (`csNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courseschedule`
--
ALTER TABLE `courseschedule`
  ADD CONSTRAINT `fk_CourseSchedule` FOREIGN KEY (`tcNo`) REFERENCES `trainingcourse` (`tcNo`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`memNo`) REFERENCES `member` (`memNo`),
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`tcNo`) REFERENCES `trainingcourse` (`tcNo`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_Message` FOREIGN KEY (`msgFrm`) REFERENCES `users` (`userNo`);

--
-- Constraints for table `messagefolder`
--
ALTER TABLE `messagefolder`
  ADD CONSTRAINT `fk_MessageFolder` FOREIGN KEY (`userNo`) REFERENCES `users` (`userNo`);

--
-- Constraints for table `receivedmessage`
--
ALTER TABLE `receivedmessage`
  ADD CONSTRAINT `fk_ReceivedMessage_msgNo` FOREIGN KEY (`msgNo`) REFERENCES `message` (`msgNo`),
  ADD CONSTRAINT `fk_ReceivedMessage_msgTo` FOREIGN KEY (`msgTo`) REFERENCES `users` (`userNo`),
  ADD CONSTRAINT `fk_ReceivedMessage_mfNo` FOREIGN KEY (`mfNo`) REFERENCES `messagefolder` (`mfNo`);

--
-- Constraints for table `trainingcourse`
--
ALTER TABLE `trainingcourse`
  ADD CONSTRAINT `fk_TrainingCourse` FOREIGN KEY (`insNo`) REFERENCES `instructor` (`insNo`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_Users_mem` FOREIGN KEY (`memNo`) REFERENCES `member` (`memNo`),
  ADD CONSTRAINT `fk_Users_int` FOREIGN KEY (`insNo`) REFERENCES `instructor` (`insNo`);

--
-- Constraints for table `user_courseschedule`
--
ALTER TABLE `user_courseschedule`
  ADD CONSTRAINT `fk_User_CourseSchedule_userNo` FOREIGN KEY (`userNo`) REFERENCES `users` (`userNo`),
  ADD CONSTRAINT `fk_User_CourseSchedule_csNo` FOREIGN KEY (`csNo`) REFERENCES `courseschedule` (`csNo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
