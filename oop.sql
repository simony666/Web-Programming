-- --------------------------------------------------------
-- Server version:               10.3.38-MariaDB-0ubuntu0.20.04.1 - Ubuntu 20.04
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;



-- Dumping structure for table s3_database.Artist
CREATE TABLE IF NOT EXISTS `Artist` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `bandName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.Artist: ~15 rows (approximately)
DELETE FROM `Artist`;
INSERT INTO `Artist` (`id`, `name`, `bandName`) VALUES
	(0000000001, 'Zhi Yen', 'bang'),
	(0000000002, 'Zhi Qin', 'bang'),
	(0000000003, 'Zhi Long', 'bang'),
	(0000000004, 'Qin Long', 'Long'),
	(0000000005, 'Qin Qin', 'Long'),
	(0000000006, 'Qin Yen', 'Long'),
	(0000000007, 'Simon', 'West'),
	(0000000008, 'Salmon', 'West'),
	(0000000009, 'Samon', 'West'),
	(0000000010, 'Eddie', 'KOK'),
	(0000000011, 'Addie Seng', 'KOK'),
	(0000000012, 'Edy Chua', 'KOK'),
	(0000000013, 'Siew You', 'KOK'),
	(0000000014, 'Qiao', 'Qiao'),
	(0000000015, 'WuNai', 'null');

-- Dumping structure for table s3_database.Performance
CREATE TABLE IF NOT EXISTS `Performance` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `performanceName` varchar(20) NOT NULL,
  `performanceType` varchar(10) NOT NULL,
  `artistName` varchar(10) NOT NULL DEFAULT 'Unknown',
  `artistId` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `artistId` (`artistId`),
  CONSTRAINT `artistId` FOREIGN KEY (`artistId`) REFERENCES `Artist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.Performance: ~11 rows (approximately)
DELETE FROM `Performance`;
INSERT INTO `Performance` (`Id`, `performanceName`, `performanceType`, `artistName`, `artistId`) VALUES
	(1, 'Tranditional Dance', 'Dance', 'Qin Long\r\n', 0000000004),
	(2, 'Morden Pop', 'pop', 'Zhi Yen', 0000000001),
	(3, 'Lion Dance', 'Dance', 'Edy Chua', 0000000012),
	(4, 'Kasyen', 'Dance', 'Zhi Long', 0000000003),
	(5, 'zibizhong', 'Dance', 'wuyu', 0000000015),
	(6, '10', 'Pop', 'wuyu', 0000000015),
	(7, 'wuyuzhong', 'Music', 'wuyu', 0000000015),
	(8, 'washen', 'Pop', 'wuyu', 0000000015),
	(9, 'Musical Performance', 'Music', 'Simon', 0000000007),
	(10, 'Orchestra', 'music', 'Simon', 0000000007),
	(11, 'abc', 'Dance', 'WuNai', 0000000015);

-- Dumping structure for table s3_database.Schedule
CREATE TABLE IF NOT EXISTS `Schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `durationHours` int(11) NOT NULL DEFAULT 2,
  `durationMinutes` int(11) NOT NULL DEFAULT 0,
  `performanceName` varchar(50) NOT NULL,
  `performanceId` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `performanceId` (`performanceId`),
  CONSTRAINT `performanceId` FOREIGN KEY (`performanceId`) REFERENCES `Performance` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.Schedule: ~6 rows (approximately)
DELETE FROM `Schedule`;
INSERT INTO `Schedule` (`id`, `date`, `startTime`, `endTime`, `durationHours`, `durationMinutes`, `performanceName`, `performanceId`) VALUES
	(3, '2023-10-10', '08:00:00', '08:20:00', 2, 25, 'Tranditional Dance', 1),
	(4, '2023-11-20', '08:20:00', '08:45:00', 2, 20, 'Kasyen', 4),
	(5, '2023-11-11', '06:00:00', '07:30:00', 4, 90, 'wuyuzhong', 7),
	(6, '2023-11-10', '20:00:00', '24:00:00', 4, 0, 'zibizhong', 5),
	(7, '2023-11-08', '07:30:00', '09:00:00', 1, 90, 'wuyuzhong', 7),
	(8, '2023-10-07', '10:00:00', '15:00:00', 5, 0, 'Orchestra', 10);

-- Dumping structure for table s3_database.Seat
CREATE TABLE IF NOT EXISTS `Seat` (
  `seatID` varchar(50) NOT NULL,
  `venue` varchar(50) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT '1',
  PRIMARY KEY (`seatID`) USING BTREE,
  KEY `venue` (`venue`),
  CONSTRAINT `venue` FOREIGN KEY (`venue`) REFERENCES `Venue` (`venueID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.Seat: ~8 rows (approximately)
DELETE FROM `Seat`;
INSERT INTO `Seat` (`seatID`, `venue`, `price`, `status`) VALUES
	('abc122', 'JB1', 100, '1'),
	('abc123', 'JB1', 100, '1'),
	('AJ001', 'JB1', 259, '1'),
	('AK001', 'KL1', 299, '1'),
	('RJ001', 'JB1', 159, '1'),
	('RK001', 'KL1', 199, '1'),
	('VJ001', 'JB1', 359, '1'),
	('VK001', 'KL1', 499, '1');

-- Dumping structure for table s3_database.Ticket
CREATE TABLE IF NOT EXISTS `Ticket` (
  `ticketID` int(11) NOT NULL AUTO_INCREMENT,
  `seat` varchar(50) NOT NULL,
  `venue` varchar(50) NOT NULL,
  PRIMARY KEY (`ticketID`) USING BTREE,
  KEY `seat` (`seat`),
  CONSTRAINT `seat` FOREIGN KEY (`seat`) REFERENCES `Seat` (`seatID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.Ticket: ~4 rows (approximately)
DELETE FROM `Ticket`;
INSERT INTO `Ticket` (`ticketID`, `seat`, `venue`) VALUES
	(1, 'AJ001', 'JB1'),
	(2, 'RJ001', 'JB1'),
	(3, 'abc123', 'JB1'),
	(4, 'AK001', 'KL1');

-- Dumping structure for table s3_database.User
CREATE TABLE IF NOT EXISTS `User` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.User: ~20 rows (approximately)
DELETE FROM `User`;
INSERT INTO `User` (`id`, `username`, `password`, `name`, `role`, `email`) VALUES
	(1, 'Simon', '123', '123', 'Admin', '123'),
	(2, 'a', '123', 'a', 'Customer', 'a@gmail.com'),
	(3, 'b', '123', 'b', 'Customer', 'b@gmail.com'),
	(4, 'c', '123', 'c', 'Customer', 'c@gmail.com'),
	(5, 'd', '123', 'd', 'Customer', 'd@gmail.com'),
	(6, 'e', '123', 'e', 'Customer', 'e@gmail.com'),
	(7, 'f', '123', 'f', 'Customer', 'f@gmail.com'),
	(8, 'g', '123', 'g', 'Customer', 'g@gmail.com'),
	(9, 'h', '123', 'h', 'Customer', 'h@gmail.com'),
	(10, 'i', '123', 'i', 'Customer', 'i@gmail.com'),
	(11, 'j', '123', 'j', 'Customer', 'j@gmail.com'),
	(12, 'k', '123', 'k', 'Customer', 'k@gmail.com'),
	(13, 'l', '123', 'l', 'Customer', 'l@gmail.com'),
	(14, 'm', '123', 'm', 'Customer', 'm@gmail.com'),
	(15, 'Qinlong', 'null', 'long long', 'Customer', 'qinlong@gmail.com'),
	(16, 'zhiyen', 'null', 'Zhi Yen', 'Customer', 'qwe@1.com'),
	(17, 'n', '123', 'n', 'Customer', 'n@gmail.com'),
	(18, 'o', '123', 'o', 'Customer', 'o@gmail.com'),
	(19, 'zibizhong', '123', 'zibizhong', 'Admin', 'zbz@gmail.com'),
	(20, 'qwer', '123', 'qwe', 'Admin', 'qwe@gmail.com');

-- Dumping structure for table s3_database.Venue
CREATE TABLE IF NOT EXISTS `Venue` (
  `venueID` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL DEFAULT '0',
  `capacity` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`venueID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table s3_database.Venue: ~3 rows (approximately)
DELETE FROM `Venue`;
INSERT INTO `Venue` (`venueID`, `location`, `capacity`) VALUES
	('JB1', 'Johor Baru', '200'),
	('KL1', 'Kuala Lumpur', '400'),
	('PN1', 'pulau penang', '200');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
