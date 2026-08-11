/*
SQLyog Ultimate v12.4.1 (64 bit)
MySQL - 11.5.2-MariaDB : Database - clinic
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`clinic` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;

USE `clinic`;

/*Table structure for table `appointments` */

DROP TABLE IF EXISTS `appointments`;

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `doctor` varchar(255) DEFAULT NULL,
  `email_account` varchar(255) DEFAULT NULL,
  `notes` date DEFAULT curdate(),
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `custom_id` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','booked','arrived','reschedule','follow_up','cancelled','in_session','completed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `appointments_ibfk_1` (`registration_id`),
  CONSTRAINT `fk_registration` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `appointments` */

insert  into `appointments`(`id`,`registration_id`,`appointment_date`,`appointment_time`,`doctor`,`email_account`,`notes`,`approved`,`created_at`,`updated_at`,`custom_id`,`user_id`,`status`) values 
(1,4,'2026-08-11','11:00:00',NULL,NULL,NULL,0,'2026-08-11 11:28:21','2026-08-11 11:28:21',NULL,0,'booked'),
(2,4,'2026-08-12','09:00:00',NULL,NULL,NULL,0,'2026-08-11 11:28:58','2026-08-11 11:46:42',NULL,0,'cancelled');

/*Table structure for table `check_up` */

DROP TABLE IF EXISTS `check_up`;

CREATE TABLE `check_up` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `blood_pressure` varchar(7) NOT NULL,
  `pulse_rate` varchar(10) NOT NULL,
  `respiration_rate` varchar(10) NOT NULL,
  `temperature` decimal(4,1) NOT NULL,
  `oxygen_saturation` varchar(10) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `checkup_date` datetime DEFAULT current_timestamp(),
  `ultrasound` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `doctor_comment` text DEFAULT NULL,
  `next_checkup_date` datetime DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `recommendation` text DEFAULT NULL,
  `pregnancy_test` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `check_up_ibfk_1` (`registration_id`),
  CONSTRAINT `check_up_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `check_up` */

/*Table structure for table `diagnosis` */

DROP TABLE IF EXISTS `diagnosis`;

CREATE TABLE `diagnosis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `recommendation` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `prescriptions` text DEFAULT NULL,
  `date_released` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_id` (`registration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `diagnosis` */

/*Table structure for table `diagnosis_types` */

DROP TABLE IF EXISTS `diagnosis_types`;

CREATE TABLE `diagnosis_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `diagnosis_types` */

insert  into `diagnosis_types`(`id`,`type`) values 
(1,'Pre-mature'),
(2,'Placenta Previa'),
(3,'Abruptio Placenta'),
(4,'Cesarian Section'),
(5,'Normal Delivery'),
(6,'Check Up');

/*Table structure for table `doctors_appointments` */

DROP TABLE IF EXISTS `doctors_appointments`;

CREATE TABLE `doctors_appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `appointment_reason` varchar(255) DEFAULT NULL,
  `appointment_status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `doctor_name` varchar(255) DEFAULT 'Dra. Chona Mendoza',
  PRIMARY KEY (`appointment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `doctors_appointments` */

insert  into `doctors_appointments`(`appointment_id`,`appointment_date`,`appointment_time`,`appointment_reason`,`appointment_status`,`created_at`,`updated_at`,`doctor_name`) values 
(1,'2025-02-18','08:00:00','for nutrition seminar','Scheduled','2025-02-11 18:20:02','2025-02-11 18:20:02','Dra. Chona Mendoza');

/*Table structure for table `files` */

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_id` (`registration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `files` */

/*Table structure for table `findings` */

DROP TABLE IF EXISTS `findings`;

CREATE TABLE `findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `findings` text NOT NULL,
  `recommendations` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `registration_id` (`registration_id`),
  CONSTRAINT `findings_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `findings` */

/*Table structure for table `laboratory_tests` */

DROP TABLE IF EXISTS `laboratory_tests`;

CREATE TABLE `laboratory_tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `ultrasound` varchar(255) NOT NULL,
  `pregnancy_test` varchar(255) NOT NULL,
  `urinalysis` varchar(255) NOT NULL,
  `test_date` date NOT NULL,
  `results` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `diagnosis_type_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_id` (`registration_id`),
  CONSTRAINT `laboratory_tests_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `laboratory_tests` */

/*Table structure for table `medical` */

DROP TABLE IF EXISTS `medical`;

CREATE TABLE `medical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `ear_nose_throat_disorders` tinyint(1) DEFAULT NULL,
  `heart_conditions_high_blood_pressure` tinyint(1) DEFAULT NULL,
  `respiratory_tuberculosis_asthma` tinyint(1) DEFAULT NULL,
  `neurologic_migraines_frequent_headaches` tinyint(1) DEFAULT NULL,
  `gonorrhea_chlamydia_syphilis` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `no_of_pregnancy` int(11) DEFAULT NULL,
  `last_menstrual` date DEFAULT NULL,
  `age_gestation` int(11) DEFAULT NULL,
  `expected_date_confinement` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_ibfk_1` (`registration_id`),
  CONSTRAINT `medical_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `medical` */

insert  into `medical`(`id`,`registration_id`,`ear_nose_throat_disorders`,`heart_conditions_high_blood_pressure`,`respiratory_tuberculosis_asthma`,`neurologic_migraines_frequent_headaches`,`gonorrhea_chlamydia_syphilis`,`last_update`,`no_of_pregnancy`,`last_menstrual`,`age_gestation`,`expected_date_confinement`) values 
(1,1,1,1,1,1,1,'2025-02-12 16:27:50',1,'2025-02-14',1,'2025-02-14'),
(2,1,2,1,2,2,1,'2025-02-12 16:30:50',1,'2025-02-12',1,'2025-02-14'),
(3,2,1,1,1,1,1,'2025-02-12 16:40:21',1,'2025-02-14',1,'2025-02-20'),
(4,3,2,2,2,2,2,'2026-08-11 09:54:14',1,'2026-08-09',1,'2026-08-29'),
(5,4,2,2,2,2,2,'2026-08-11 10:35:59',1,'2026-08-11',1,'2026-08-28'),
(6,5,2,2,2,2,2,'2026-08-11 11:49:14',1,'2026-08-28',1,'2026-08-28');

/*Table structure for table `online_appointments` */

DROP TABLE IF EXISTS `online_appointments`;

CREATE TABLE `online_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `STATUS` enum('pending','booked','arrived','reschedule','follow_up','cancelled','in_session','completed') NOT NULL DEFAULT 'pending',
  `last_booking_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `online_appointments` */

/*Table structure for table `registration` */

DROP TABLE IF EXISTS `registration`;

CREATE TABLE `registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `philhealth_id` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed') DEFAULT NULL,
  `husband_phone` varchar(50) DEFAULT NULL,
  `patient_contact_no` varchar(50) DEFAULT NULL,
  `birthday` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `husband` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `custom_id` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `appointment_status` enum('pending','booked','arrived','reschedule','follow_up','cancelled','in_session','completed','missed','reminder_sent') DEFAULT 'pending',
  `email` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `doctor` varchar(255) DEFAULT NULL,
  `notes` datetime DEFAULT current_timestamp(),
  `next_checkup_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_custom_id` (`custom_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `registration` */

insert  into `registration`(`id`,`patient_id`,`philhealth_id`,`name`,`mname`,`lname`,`marital_status`,`husband_phone`,`patient_contact_no`,`birthday`,`address`,`age`,`husband`,`occupation`,`is_deleted`,`custom_id`,`created_at`,`last_update`,`appointment_date`,`appointment_time`,`appointment_status`,`email`,`updated_at`,`doctor`,`notes`,`next_checkup_date`) values 
(1,0,'70989089890','mama','papa','ate ','single','908970','08080980-','2000-10-10','MANILA',24,'HELP','ME',0,NULL,'2025-02-12 16:27:32','2025-02-12 16:42:22','2025-02-14','14:30:00','booked','mapap68669@minduls.com','2025-02-12 16:42:22',NULL,'2025-02-12 16:27:32',NULL),
(2,0,'98970798787','ka','kak','ka','single','97897087','9070990890','2000-10-10','kajkakjl',24,'kasdkljaskdj','ajkajkjal',0,NULL,'2025-02-12 16:39:59','2025-02-12 16:40:45','2025-02-13','09:00:00','booked','yicoj41447@minduls.com','2025-02-12 16:40:45',NULL,'2025-02-12 16:39:59',NULL),
(3,0,'098809809890','User1','User1','User1','married','098090907887','090889898','2000-09-09','User1 Manila',25,'User2','Husband',0,NULL,'2026-08-11 09:53:59','2026-08-11 11:27:03','2026-08-18','09:00:00','follow_up','user1@user1.com','2026-08-11 11:27:03','Dr. Chona Mendoza','2026-08-11 10:21:36',NULL),
(4,0,'09890898989','User3','User3','User3','single','9807098098','090880989080','2001-09-10','User3 Manila',24,'User3','User3',0,NULL,'2026-08-11 10:35:30','2026-08-11 11:27:53','2026-08-11','11:00:00','pending','tinasagad0@gmail.com','2026-08-11 11:27:53','Dr. Chona Mendoza','2026-08-11 11:27:53',NULL),
(5,0,'667090-8087','Abm1','Agustin','Aham','single','43948320948','08909','2019-02-12','Ict ',7,'Ict','Ict',0,NULL,'2026-08-11 11:48:41','2026-08-11 12:57:14','2026-08-11','16:00:00','booked','myeclass2021@gmail.com','2026-08-11 12:57:14','Dr. Chona Mendoza','2026-08-11 11:48:41',NULL);

/*Table structure for table `scheduling_settings` */

DROP TABLE IF EXISTS `scheduling_settings`;

CREATE TABLE `scheduling_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` varchar(10) NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `scheduling_settings` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_days` varchar(255) NOT NULL,
  `open_hours` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`open_days`,`open_hours`) values 
(1,'Monday,Tuesday,Wednesday,Thursday,Friday','09:00-17:00');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_level` enum('admin','user','secretary','doctor') NOT NULL DEFAULT 'secretary',
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`firstname`,`lastname`,`birthday`,`mobile`,`password`,`created_at`,`user_level`,`username`) values 
(1,'admin@admin.com','admin','admin','2026-08-11','09000000000','$2y$10$gMpAy0d6siAoG/l/GF9V5esXHRBNkkUT2/iUjFale4l6AWZnAOLyC','2026-08-11 09:50:13','admin','admin'),
(2,'nurse@nurse.com','nurse','nurse','2026-08-11','09000000','$2y$10$Bu.k2a8oP/jsfM0NPxDPTuv1O8rttlAL7qXP4XRAvJphehAgUT8Wa','2026-08-11 09:51:00','secretary','nurse'),
(3,'doctor@doctor.com','doctor','doctor','2026-08-11','09000000000','$2y$10$eZCaSmumqkARk/oWlPft9eHA3LhqzxhQ84cxNwbw/K9JZ5j.Ehi36','2026-08-11 09:51:30','doctor','doctor');

/*Table structure for table `vital_signs` */

DROP TABLE IF EXISTS `vital_signs`;

CREATE TABLE `vital_signs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `blood_pressure_systolic` int(3) NOT NULL,
  `blood_pressure_diastolic` int(3) NOT NULL,
  `pulse_rate` int(3) NOT NULL,
  `respiration_rate` int(3) NOT NULL,
  `temperature` decimal(4,1) NOT NULL,
  `oxygen_saturation` int(3) DEFAULT NULL,
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `checkup_date` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `vital_signs_ibfk_1` (`registration_id`),
  CONSTRAINT `vital_signs_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

/*Data for the table `vital_signs` */

insert  into `vital_signs`(`id`,`registration_id`,`blood_pressure_systolic`,`blood_pressure_diastolic`,`pulse_rate`,`respiration_rate`,`temperature`,`oxygen_saturation`,`height`,`weight`,`bmi`,`checkup_date`,`created_at`) values 
(1,1,1,1,1,1,1.0,1,1.00,1.00,1.00,'2025-02-12 16:30:36','2025-02-12 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
