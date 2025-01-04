-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 02:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `type`, `email`, `password`, `name`) VALUES
(1, 'admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin'),
(14, 'teacher', 'likhitha@gmail.com', '202cb962ac59075b964b07152d234b70', 'likhitha'),
(18, 'teacher', 'teacher@gmailcom', '202cb962ac59075b964b07152d234b70', 'Varsha'),
(20, 'student', 'pinky@gmail.com', '202cb962ac59075b964b07152d234b70', 'pinky'),
(21, 'student', 'rosee@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'rose'),
(22, 'student', 'mahi@gmail.com', '202cb962ac59075b964b07152d234b70', 'mahima');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `attendance_month` varchar(7) NOT NULL,
  `attendance_value` enum('present','absent') NOT NULL,
  `student_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `current_session` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `attendance_month`, `attendance_value`, `student_id`, `attendance_date`, `modified_date`, `current_session`) VALUES
(1, '2024-12', 'present', 9, '2024-12-05', '2024-12-05 13:40:28', '2024-12-05 13:40:28'),
(2, '2024-12', 'present', 7, '2024-12-05', '2024-12-05 15:45:52', '2024-12-05 14:11:00'),
(4, '2024-12', 'present', 9, '2024-12-02', '2024-12-05 15:28:19', '2024-12-05 15:28:19'),
(6, '2024-12', 'absent', 8, '2024-12-05', '2024-12-05 15:53:53', '2024-12-05 15:53:53'),
(7, '2024-11', 'present', 9, '2024-11-25', '2024-12-06 12:17:04', '2024-12-06 12:17:04'),
(8, '2024-11', 'absent', 9, '2024-11-04', '2024-12-06 12:17:26', '2024-12-06 12:17:26'),
(9, '2024-12', 'absent', 9, '2024-12-14', '2024-12-06 18:54:26', '2024-12-06 18:54:26'),
(10, '2024-12', 'present', 8, '2024-12-29', '2024-12-06 18:59:53', '2024-12-06 18:59:53');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `category` text NOT NULL,
  `duration` text NOT NULL,
  `date` datetime NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `description`, `created_at`) VALUES
(10, 'children day', '2024-11-14', 'hello', '2024-11-30 13:17:51'),
(12, 'school day', '2024-12-28', 'even parents are allowed to come', '2024-12-01 18:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','reviewed','closed') DEFAULT 'new',
  `created_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(29, 1, 21, 'congradulations', '2024-12-04 13:59:24'),
(52, 1, 14, 'happy teacher\'s day', '2024-12-04 14:22:19'),
(53, 1, 18, 'happy teacher\'s day', '2024-12-04 14:22:19'),
(54, 14, 20, 'gm', '2024-12-04 14:22:26'),
(55, 14, 21, 'gm', '2024-12-04 14:22:26'),
(56, 14, 20, 'read and come', '2024-12-04 14:34:59'),
(57, 14, 21, 'read and come', '2024-12-04 14:34:59'),
(59, 1, 14, 'hello', '2024-12-04 14:51:36'),
(60, 1, 18, 'hello', '2024-12-04 14:51:36'),
(61, 1, 20, 'hello', '2024-12-04 14:51:36'),
(63, 14, 1, 'hello gm', '2024-12-04 14:55:07'),
(64, 1, 21, 'hello', '2024-12-04 14:55:22'),
(65, 21, 1, 'thaku', '2024-12-04 15:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `metadata`
--

CREATE TABLE `metadata` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `meta_key` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metadata`
--

INSERT INTO `metadata` (`id`, `item_id`, `meta_key`, `meta_value`) VALUES
(95, 50, 'section', 'A'),
(98, 38, 'section', 'B'),
(99, 68, 'section', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT 1,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `type` varchar(100) NOT NULL,
  `publish_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `status` varchar(50) NOT NULL,
  `parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author`, `title`, `description`, `type`, `publish_date`, `modified_date`, `status`, `parent`) VALUES
(38, 1, '8', 'description', 'class', '2024-11-29 12:55:26', '2024-11-29 12:55:26', 'publish', 0),
(50, 1, '9', 'description', 'class', '2024-11-29 12:55:34', '2024-11-29 12:55:34', 'publish', 0),
(64, 1, 'A', 'description', 'section', '2024-11-29 12:55:04', '0000-00-00 00:00:00', 'publish', 0),
(66, 1, 'Maths', 'description', 'subject', '2024-11-29 12:56:05', '0000-00-00 00:00:00', 'publish', 0),
(67, 1, 'Science', 'description', 'subject', '2024-11-29 12:56:11', '0000-00-00 00:00:00', 'publish', 0),
(68, 1, '10', 'description', 'class', '2024-12-05 04:49:37', '0000-00-00 00:00:00', 'publish', 0),
(70, 1, 'B', 'description', 'section', '2024-12-05 05:01:38', '0000-00-00 00:00:00', 'publish', 0),
(71, 1, 'C', 'description', 'section', '2024-12-05 07:51:38', '0000-00-00 00:00:00', 'publish', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `title`) VALUES
(1, 'C'),
(3, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_mobile` varchar(15) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_mobile` varchar(15) DEFAULT NULL,
  `parents_address` text DEFAULT NULL,
  `parents_country` varchar(100) DEFAULT NULL,
  `parents_state` varchar(100) DEFAULT NULL,
  `parents_zip` varchar(10) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `doa` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `account_id`, `dob`, `mobile`, `address`, `country`, `state`, `zip`, `father_name`, `father_mobile`, `mother_name`, `mother_mobile`, `parents_address`, `parents_country`, `parents_state`, `parents_zip`, `class`, `section`, `doa`) VALUES
(7, 20, '2024-11-16', '9353756042', 'Shri Nagesha Krupa', 'India', 'Karnataka', '576107', 'N Vishwanath Nayak', '9353756042', 'Veena', '9353756042', 'Shri Nagesha Krupa', 'India', 'Karnataka', '576107', '8', 'B', '2024-11-14'),
(8, 21, '2024-11-22', '8762926867', 'Shri Nagesha Krupa', 'India', 'Karnataka', '576107', 'N Vishwanath Nayak', '08762926867', 'Veena', '08762926867', 'Shri Nagesha Krupa', 'India', 'Karnataka', '576107', '9', 'A', '2024-11-30'),
(9, 22, '2024-12-05', '123', 'shri nagesh krupa,nagarabettu house,parkala', 'India', '-Select-', '576107', 'abc', '987', 'efgg', '09353756042', 'shri nagesh krupa,nagarabettu house,parkala', 'India', '-Select-', '576107', '10', 'A', '2024-12-05');

-- --------------------------------------------------------

--
-- Table structure for table `study_materials`
--

CREATE TABLE `study_materials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_materials`
--

INSERT INTO `study_materials` (`id`, `title`, `description`, `file_name`, `file_path`, `class_id`, `subject_id`, `uploaded_at`) VALUES
(26, 'written notes', 'module 7', 'Document 8.pdf', '/uploads/Document 8.pdf', 38, 67, '2024-12-03 13:24:36'),
(28, 'textbook', 'module 1', 'Blue and Pink Professional Business Strategy Presentation.pdf', '/uploads/Blue and Pink Professional Business Strategy Presentation.pdf', 50, 67, '2024-12-03 13:51:08'),
(29, 'hello world', 'module 5', 'exp8-students.JPG', '/uploads/exp8-students.JPG', 50, 67, '2024-12-03 13:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `account_id`, `qualification`, `department`, `experience`) VALUES
(3, 14, 'teacher', 'maths', 3),
(4, 18, 'teacher', 'science', 2);

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_slot` varchar(50) NOT NULL,
  `class_title` varchar(255) DEFAULT NULL,
  `section_title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `day`, `subject_id`, `time_slot`, `class_title`, `section_title`) VALUES
(0, 'Thursday', 67, '11.30-12.15', '10', 'B'),
(0, 'Wednesday', 66, '9.00-9.45', '9', 'A'),
(0, 'Thursday', 67, '9.45-10.30', '10', 'A'),
(0, 'Monday', 66, '1.45-2.30', '9', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `usermeta`
--

CREATE TABLE `usermeta` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_key` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`attendance_date`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `metadata`
--
ALTER TABLE `metadata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `usermeta`
--
ALTER TABLE `usermeta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `metadata`
--
ALTER TABLE `metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `study_materials`
--
ALTER TABLE `study_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usermeta`
--
ALTER TABLE `usermeta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD CONSTRAINT `study_materials_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `study_materials_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
