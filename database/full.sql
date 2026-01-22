-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2026 at 12:11 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `subspace`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `link_url`, `media_url`, `is_hidden`, `created_at`, `updated_at`) VALUES
(1, 1, 'Just deployed my first machine learning model to production! It\'s been a challenging but rewarding journey. #AI #ML #Tech', NULL, 'https://images.unsplash.com/photo-1555949963-aa79dcee981c', 0, '2023-11-01 09:15:00', NULL),
(2, 2, 'Sunset in Bali never gets old. The colors today were absolutely breathtaking! 🌅', NULL, 'https://images.unsplash.com/photo-1537996194471-e657df975ab4', 0, '2023-11-02 14:30:00', NULL),
(3, 3, 'Anyone else excited about the new JavaScript framework announcements? The ecosystem moves so fast! #webdev #javascript', 'https://dev.to/news', NULL, 0, '2023-11-03 10:45:00', NULL),
(4, 4, 'Just arrived in Tokyo! The food here is incredible. First stop: ramen! 🍜', NULL, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624', 0, '2023-11-04 18:20:00', NULL),
(5, 5, 'Working on a new album. This one\'s going to be different - experimenting with electronic folk fusion. Thoughts? #musicproduction', NULL, NULL, 0, '2023-11-05 20:10:00', NULL),
(6, 6, 'Just finished \"Project Hail Mary\" by Andy Weir. What an incredible book! The science, the characters, the ending... perfection. 📚', NULL, NULL, 0, '2023-11-06 11:30:00', NULL),
(7, 7, 'New high score in the tournament today! The competition was fierce but we pulled through. #esports #gaming', 'https://twitch.tv', NULL, 0, '2023-11-07 22:45:00', NULL),
(8, 8, 'Try this 15-minute morning workout routine: 1. Jumping jacks 2. Push-ups 3. Squats 4. Plank Repeat 3x! #fitness #workout', NULL, NULL, 0, '2023-11-08 07:15:00', NULL),
(9, 9, 'Experimented with Korean-Mexican fusion tonight: Bulgogi tacos with kimchi slaw. Surprisingly delicious! #foodie #recipes', NULL, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b', 0, '2023-11-09 19:30:00', NULL),
(10, 10, 'New digital painting complete! This one was inspired by Japanese folklore. Swipe to see the process. #art #digitalart', NULL, 'https://images.unsplash.com/photo-1541961017774-22349e4a1262', 0, '2023-11-10 16:40:00', NULL),
(11, 1, 'Looking for recommendations on good MLOps tools. Currently exploring Kubeflow vs MLflow. What\'s your experience? #MLOps', NULL, NULL, 0, '2023-11-11 13:20:00', NULL),
(12, 13, 'Just got my hands on the new smartphone. The camera improvements are revolutionary! Full review coming soon. #techreview', NULL, NULL, 0, '2023-11-12 15:55:00', NULL),
(13, 14, 'Planted 50 trees today with the local community. Every little bit helps! #environment #sustainability', NULL, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef', 0, '2023-11-13 12:10:00', NULL),
(14, 15, 'Just watched the latest sci-fi film. Without spoilers: the visual effects were stunning but the plot needed work. #moviereview', NULL, NULL, 0, '2023-11-14 21:05:00', NULL),
(15, 2, 'Remote work from a beach cafe in Thailand. This is the life! #digitalnomad #remotework', NULL, 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963', 0, '2023-11-15 08:30:00', NULL),
(16, 16, 'Just finished my first marathon! The feeling of crossing that finish line is indescribable. #running #marathon', NULL, 'https://images.unsplash.com/photo-1552674605-db6ffd8facb5', 0, '2023-11-16 13:45:00', NULL),
(17, 17, 'Learning Python for data analysis. Any recommendations for good courses or books? #python #datascience', 'https://www.datacamp.com', NULL, 0, '2023-11-17 10:20:00', NULL),
(18, 18, 'My succulent collection is growing! Just added three new varieties to the family. #plants #gardening', NULL, 'https://images.unsplash.com/photo-1485955900006-10f4d324d411', 0, '2023-11-18 14:15:00', NULL),
(19, 19, 'Started learning to play the piano today. My fingers feel so clumsy! Any tips for beginners? #piano #music', NULL, NULL, 0, '2023-11-19 16:30:00', NULL),
(20, 20, 'Just adopted the cutest rescue dog! Meet Luna 🐶 #dogsofinstagram #rescuedog', NULL, 'https://images.unsplash.com/photo-1552053831-71594a27632d', 0, '2023-11-20 11:25:00', NULL),
(21, 21, 'Working on a sustainable fashion line using recycled materials. Launching next month! #sustainablefashion', NULL, NULL, 0, '2023-11-21 15:40:00', NULL),
(22, 22, 'The Northern Lights last night were absolutely magical. Nature never ceases to amaze me. #northernlights #travel', NULL, 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78', 0, '2023-11-22 20:10:00', NULL),
(23, 23, 'Just published my first research paper in a peer-reviewed journal! #academia #research', 'https://arxiv.org', NULL, 0, '2023-11-23 09:50:00', NULL),
(24, 24, 'Baking sourdough bread has become my new therapy. The smell of fresh bread is everything. #baking #sourdough', NULL, 'https://images.unsplash.com/photo-1509440159596-0249088772ff', 0, '2023-11-24 08:05:00', NULL),
(25, 25, 'My vinyl collection just hit 500 records! Any rare record collectors out there? #vinyl #musiccollection', NULL, NULL, 0, '2023-11-25 18:20:00', NULL),
(26, 26, 'Just completed a 30-day yoga challenge. Feeling more centered and flexible than ever. #yoga #mindfulness', NULL, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b', 0, '2023-11-26 07:30:00', NULL),
(27, 27, 'Started learning Japanese! Konnichiwa! 日本語を勉強しています。Any language exchange partners? #languagelearning', NULL, NULL, 0, '2023-11-27 12:45:00', NULL),
(28, 28, 'My photography exhibition opens next week! So nervous and excited. #photography #artexhibition', NULL, 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0', 0, '2023-11-28 14:55:00', NULL),
(29, 29, 'Just invested in my first cryptocurrency. To the moon? 🚀 #crypto #investing', 'https://coinmarketcap.com', NULL, 0, '2023-11-29 16:10:00', NULL),
(30, 30, 'Finished knitting my first sweater! It only took three months and countless mistakes. #knitting #diy', NULL, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b', 0, '2023-11-30 19:25:00', NULL),
(31, 31, 'Working remotely from Portugal has been life-changing. The people, the food, the weather... perfection. #digitalnomad', NULL, 'https://images.unsplash.com/photo-1518426364514-49c60a421e47', 0, '2023-12-01 10:15:00', NULL),
(32, 32, 'Just finished building my first gaming PC! The RGB lights are probably the most important part, right? #gamingpc #buildapc', NULL, NULL, 0, '2023-12-02 13:40:00', NULL),
(33, 33, 'My urban garden is thriving! Fresh tomatoes, herbs, and peppers right from my balcony. #urbangardening #organic', NULL, 'https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5', 0, '2023-12-03 09:05:00', NULL),
(34, 34, 'Meditation has completely changed my perspective on life. Starting the day with 20 minutes of mindfulness. #meditation', NULL, NULL, 0, '2023-12-04 06:20:00', NULL),
(35, 35, 'Just submitted my application for a PhD program! Fingers crossed. #phd #academiclife', NULL, NULL, 0, '2023-12-05 17:30:00', NULL),
(36, 36, 'The new coffee shop in town has the best latte art I\'ve ever seen. #coffee #latteart', NULL, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574', 0, '2023-12-06 08:45:00', NULL),
(37, 37, 'Learning to code at 40! Never too late to start something new. #coding #careerchange', NULL, NULL, 0, '2023-12-07 11:50:00', NULL),
(38, 38, 'My miniature painting hobby has taken over my life. Currently working on a dragon figurine. #miniaturepainting #hobby', NULL, 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0', 0, '2023-12-08 15:15:00', NULL),
(39, 39, 'Just ran my first 10k! Never thought I could do it. #running #10k', NULL, 'https://images.unsplash.com/photo-1552674605-db6ffd8facb5', 0, '2023-12-09 10:25:00', NULL),
(40, 40, 'Working on a novel set in 1920s Paris. Historical research is half the fun! #writing #novel', NULL, NULL, 0, '2023-12-10 14:35:00', NULL),
(41, 41, 'My home automation system is getting out of hand. My lights now respond to my mood. #smarthome #iot', NULL, NULL, 0, '2023-12-11 19:40:00', NULL),
(42, 42, 'Just started a book club with friends! First book: \"Klara and the Sun\" #bookclub #reading', NULL, 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c', 0, '2023-12-12 12:55:00', NULL),
(43, 43, 'Learning pottery has been humbling. My bowls look more like abstract art than functional dishes. #pottery #ceramics', NULL, 'https://images.unsplash.com/photo-1574732011388-8e9d1efa5c93', 0, '2023-12-13 16:20:00', NULL),
(44, 44, 'My podcast just hit 1000 subscribers! Thank you to everyone who listens. #podcast #contentcreation', 'https://anchor.fm', NULL, 0, '2023-12-14 18:30:00', NULL),
(45, 45, 'Urban exploration photography has become my passion. There\'s beauty in decay. #urbex #photography', NULL, 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0', 0, '2023-12-15 13:45:00', NULL),
(46, 46, 'Just completed a certification in UX design. Looking to transition into this field! #uxdesign #career', NULL, NULL, 0, '2023-12-16 09:10:00', NULL),
(47, 47, 'My homemade hot sauce recipe is finally perfect! Just the right balance of heat and flavor. #hotsauce #cooking', NULL, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b', 0, '2023-12-17 11:25:00', NULL),
(48, 48, 'Building a treehouse for my kids. Childhood dreams coming true. #diy #treehouse', NULL, 'https://images.unsplash.com/photo-151840761601-74b6ac5d3c14', 0, '2023-12-18 15:40:00', NULL),
(49, 49, 'Just returned from a silent meditation retreat. My mind has never felt clearer. #meditation #retreat', NULL, NULL, 0, '2023-12-19 07:55:00', NULL),
(50, 50, 'Started a YouTube channel about sustainable living. First video is up! #sustainability #youtube', 'https://youtube.com', NULL, 0, '2023-12-20 14:20:00', NULL),
(51, 51, 'My homemade kombucha is finally carbonated perfectly! Second fermentation success. #kombucha #fermentation', NULL, 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136', 0, '2023-12-21 10:35:00', NULL),
(52, 52, 'Learning to surf at 35. Wiped out more times than I can count, but caught my first wave today! #surfing', NULL, 'https://images.unsplash.com/photo-1506929562872-bb421503ef21', 0, '2023-12-22 16:50:00', NULL),
(53, 53, 'Just launched my freelance graphic design business! Portfolio is live. #freelance #graphicdesign', 'https://behance.net', NULL, 0, '2023-12-23 12:05:00', NULL),
(54, 54, 'My astronomy hobby led me to photograph the Orion Nebula last night. Space is incredible. #astronomy #astrophotography', NULL, 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564', 0, '2023-12-24 20:15:00', NULL),
(55, 55, 'Starting a community garden project in our neighborhood. Anyone want to join? #communitygarden #volunteer', NULL, 'https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5', 0, '2023-12-25 09:30:00', NULL),
(56, 56, 'Just finished restoring a vintage typewriter from 1952. It works beautifully! #vintage #typewriter', NULL, 'https://images.unsplash.com/photo-1518709268805-4e9042af2176', 0, '2023-12-26 13:45:00', NULL),
(57, 57, 'My mindfulness app just hit 10,000 downloads! Working on new features for next update. #mindfulness #appdevelopment', NULL, NULL, 0, '2023-12-27 11:20:00', NULL),
(58, 58, 'Learning traditional Japanese woodworking techniques. The precision required is incredible. #woodworking #japanese', NULL, 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f', 0, '2023-12-28 15:35:00', NULL),
(59, 59, 'Just adopted a second cat. Now I\'m officially a crazy cat person. #cats #catsofinstagram', NULL, 'https://images.unsplash.com/photo-1514888286974-6d03bde4ba14', 0, '2023-12-29 18:50:00', NULL),
(60, 60, 'Started a bullet journal to organize my life. The aesthetic spreads are half the fun. #bulletjournal #planning', NULL, 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c', 0, '2023-12-30 10:05:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `user_id`, `content`, `is_hidden`, `created_at`) VALUES
(1, 1, 3, 'Congratulations Alex! Which framework did you use? TensorFlow or PyTorch?', 0, '2023-11-01 11:20:00'),
(2, 1, 1, '@coding_wizard Thanks! I went with PyTorch - really loving their dynamic computation graphs.', 0, '2023-11-01 11:45:00'),
(3, 2, 4, 'Bali is magical! Have you been to Ubud yet? The rice terraces are incredible.', 0, '2023-11-02 16:45:00'),
(4, 2, 2, '@travel_bug Not yet! That\'s next on my list for tomorrow. Any specific recommendations?', 0, '2023-11-02 17:10:00'),
(5, 3, 1, 'The JS ecosystem exhaustion is real! Sometimes I miss the jQuery days 😅', 0, '2023-11-03 11:50:00'),
(6, 4, 5, 'Try the tsukemen at Rokurinsha in Tokyo Station! Best I\'ve ever had.', 0, '2023-11-04 20:30:00'),
(7, 5, 6, 'Electronic folk fusion sounds fascinating! Any artists that inspired this direction?', 0, '2023-11-05 21:25:00'),
(8, 6, 10, 'That book is on my list! How does it compare to The Martian?', 0, '2023-11-06 13:40:00'),
(9, 6, 6, '@art_soul I actually think it\'s better! The character development is more nuanced.', 0, '2023-11-06 14:15:00'),
(10, 7, 9, 'What game was this for? Missed the stream unfortunately.', 0, '2023-11-07 23:45:00'),
(11, 7, 7, '@foodie_expert It was the Valorant Champions Tour qualifiers! VOD is on my channel.', 0, '2023-11-08 00:20:00'),
(12, 8, 2, 'Thanks for sharing! Going to try this routine tomorrow morning.', 0, '2023-11-08 08:45:00'),
(13, 9, 1, 'That sounds amazing! Would you share the recipe?', 0, '2023-11-09 21:10:00'),
(14, 10, 8, 'Stunning work as always Chloe! The color palette is beautiful.', 0, '2023-11-10 18:20:00'),
(15, 11, 3, 'We\'re using MLflow at my company and it\'s been great for experiment tracking.', 0, '2023-11-11 15:10:00'),
(16, 13, 1, 'Amazing initiative! How can others get involved with similar projects?', 0, '2023-11-13 13:30:00'),
(17, 14, 15, 'I felt the same way! The third act really fell apart for me.', 0, '2023-11-14 22:45:00'),
(18, 15, 1, 'Living the dream! How\'s the internet speed there for remote work?', 0, '2023-11-15 10:10:00'),
(19, 16, 8, 'Congratulations on the marathon! That\'s an incredible achievement.', 0, '2023-11-16 15:30:00'),
(20, 17, 1, 'Check out "Python for Data Analysis" by Wes McKinney - it\'s the pandas creator!', 0, '2023-11-17 11:45:00'),
(21, 18, 14, 'Your succulents look amazing! What\'s your watering schedule?', 0, '2023-11-18 16:20:00'),
(22, 19, 5, 'Start with proper finger positioning! It makes everything easier later on.', 0, '2023-11-19 18:05:00'),
(23, 20, 9, 'Luna is adorable! Rescue dogs are the best. ❤️', 0, '2023-11-20 13:10:00'),
(24, 21, 14, 'Sustainable fashion is so important! Can\'t wait to see your line.', 0, '2023-11-21 17:25:00'),
(25, 22, 2, 'Where did you see the Northern Lights? It\'s on my bucket list!', 0, '2023-11-22 21:45:00'),
(26, 23, 1, 'Congratulations! What field is your research in?', 0, '2023-11-23 11:15:00'),
(27, 24, 9, 'Sourdough is tricky! What flour blend do you use?', 0, '2023-11-24 09:40:00'),
(28, 25, 5, '500 records is impressive! Any rare jazz albums?', 0, '2023-11-25 19:55:00'),
(29, 26, 34, 'Yoga changed my life too! Which style do you practice?', 0, '2023-11-26 08:45:00'),
(30, 27, 4, '日本語が上手ですね！I\'m also learning Japanese.', 0, '2023-11-27 14:20:00'),
(31, 28, 10, 'Break a leg at your exhibition! What\'s the theme?', 0, '2023-11-28 16:30:00'),
(32, 29, 13, 'Welcome to crypto! Remember to diversify and never invest more than you can lose.', 0, '2023-11-29 17:45:00'),
(33, 30, 30, 'Your first sweater looks great! What yarn did you use?', 0, '2023-11-30 20:50:00'),
(34, 31, 2, 'Portugal is my favorite digital nomad destination too! Which city?', 0, '2023-12-01 12:00:00'),
(35, 32, 7, 'The RGB definitely adds at least 10 FPS! Nice build.', 0, '2023-12-02 15:15:00'),
(36, 33, 14, 'Urban gardening goals! What are you growing?', 0, '2023-12-03 10:40:00'),
(37, 34, 49, 'Meditation is life-changing. Which technique do you practice?', 0, '2023-12-04 07:55:00'),
(38, 35, 23, 'Good luck with your PhD application! What field?', 0, '2023-12-05 18:45:00'),
(39, 36, 9, 'That latte art is incredible! Which coffee shop is this?', 0, '2023-12-06 10:20:00'),
(40, 37, 1, 'Never too late! I started coding at 32 and it changed my life.', 0, '2023-12-07 13:25:00'),
(41, 38, 10, 'Miniature painting requires so much patience! Do you use acrylics?', 0, '2023-12-08 16:40:00'),
(42, 39, 16, 'Congratulations on your first 10k! The running bug is real.', 0, '2023-12-09 11:50:00'),
(43, 40, 6, '1920s Paris sounds fascinating! Are you focusing on historical accuracy?', 0, '2023-12-10 16:10:00'),
(44, 41, 3, 'Home automation is addictive! What system are you using?', 0, '2023-12-11 20:55:00'),
(45, 42, 6, 'Great choice for a book club! Kazuo Ishiguro is brilliant.', 0, '2023-12-12 14:30:00'),
(46, 43, 24, 'Pottery is so therapeutic! Don\'t worry about perfection - embrace the wobbles.', 0, '2023-12-13 17:45:00'),
(47, 44, 1, 'Congratulations on 1000 subscribers! What\'s your podcast about?', 0, '2023-12-14 19:55:00'),
(48, 45, 28, 'Urban exploration photography is fascinating. Stay safe out there!', 0, '2023-12-15 15:20:00'),
(49, 46, 3, 'UX design is a great field! Which certification did you complete?', 0, '2023-12-16 10:45:00'),
(50, 47, 9, 'Hot sauce recipe please! I love experimenting with different peppers.', 0, '2023-12-17 13:10:00'),
(51, 48, 18, 'Treehouse building is my dream project! Are you using plans or winging it?', 0, '2023-12-18 17:15:00'),
(52, 49, 34, 'Silent retreats are transformative. How many days were you there?', 0, '2023-12-19 09:20:00'),
(53, 50, 14, 'Subscribed to your channel! What\'s the first video about?', 0, '2023-12-20 15:45:00'),
(54, 51, 9, 'Kombucha brewing is so satisfying! What flavor are you making?', 0, '2023-12-21 12:10:00'),
(55, 52, 2, 'Surfing is on my bucket list! Where are you learning?', 0, '2023-12-22 18:25:00'),
(56, 53, 10, 'Congratulations on starting your business! Portfolio looks great.', 0, '2023-12-23 13:40:00'),
(57, 54, 13, 'Amazing photo of the Orion Nebula! What telescope do you use?', 0, '2023-12-24 21:30:00'),
(58, 55, 14, 'I\'d love to join the community garden project! Where is it located?', 0, '2023-12-25 10:55:00'),
(59, 56, 23, 'Vintage typewriters have such beautiful mechanics. Great restoration!', 0, '2023-12-26 15:20:00'),
(60, 57, 1, '10,000 downloads is impressive! What programming language did you use?', 0, '2023-12-27 12:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`post_id`, `user_id`, `created_at`) VALUES
(1, 2, '2023-11-01 10:30:00'),
(1, 3, '2023-11-01 11:45:00'),
(1, 5, '2023-11-01 14:20:00'),
(1, 7, '2023-11-01 15:30:00'),
(1, 9, '2023-11-01 16:45:00'),
(2, 1, '2023-11-02 16:10:00'),
(2, 4, '2023-11-02 17:25:00'),
(2, 6, '2023-11-02 18:40:00'),
(2, 8, '2023-11-02 19:15:00'),
(2, 10, '2023-11-02 20:30:00'),
(3, 1, '2023-11-03 11:30:00'),
(3, 7, '2023-11-03 12:45:00'),
(3, 9, '2023-11-03 13:20:00'),
(3, 13, '2023-11-03 14:35:00'),
(3, 15, '2023-11-03 15:50:00'),
(4, 1, '2023-11-04 19:40:00'),
(4, 2, '2023-11-04 19:40:00'),
(4, 5, '2023-11-04 20:15:00'),
(4, 10, '2023-11-04 21:30:00'),
(4, 12, '2023-11-04 22:45:00'),
(5, 6, '2023-11-05 21:45:00'),
(5, 8, '2023-11-05 22:10:00'),
(5, 10, '2023-11-05 23:25:00'),
(5, 14, '2023-11-06 00:40:00'),
(5, 16, '2023-11-06 01:55:00'),
(6, 1, '2023-11-06 12:45:00'),
(6, 9, '2023-11-06 13:20:00'),
(6, 14, '2023-11-06 14:35:00'),
(6, 18, '2023-11-06 15:50:00'),
(6, 22, '2023-11-06 17:05:00'),
(7, 3, '2023-11-07 23:10:00'),
(7, 5, '2023-11-08 00:25:00'),
(7, 8, '2023-11-08 01:40:00'),
(7, 12, '2023-11-08 02:55:00'),
(7, 16, '2023-11-08 04:10:00'),
(8, 4, '2023-11-08 08:30:00'),
(8, 7, '2023-11-08 09:15:00'),
(8, 9, '2023-11-08 10:40:00'),
(8, 11, '2023-11-08 11:55:00'),
(8, 15, '2023-11-08 13:10:00'),
(9, 1, '2023-11-09 20:45:00'),
(9, 2, '2023-11-09 21:20:00'),
(9, 5, '2023-11-09 22:10:00'),
(9, 8, '2023-11-09 23:25:00'),
(9, 12, '2023-11-10 00:40:00'),
(10, 3, '2023-11-10 17:30:00'),
(10, 6, '2023-11-10 18:45:00'),
(10, 9, '2023-11-10 19:20:00'),
(10, 12, '2023-11-10 20:35:00'),
(10, 15, '2023-11-10 21:50:00'),
(11, 3, '2023-11-11 14:40:00'),
(11, 13, '2023-11-11 15:25:00'),
(11, 17, '2023-11-11 16:40:00'),
(11, 21, '2023-11-11 17:55:00'),
(11, 25, '2023-11-11 19:10:00'),
(12, 1, '2023-11-12 16:50:00'),
(12, 4, '2023-11-12 17:35:00'),
(12, 7, '2023-11-12 18:50:00'),
(12, 10, '2023-11-12 20:05:00'),
(12, 13, '2023-11-12 21:20:00'),
(13, 2, '2023-11-13 13:45:00'),
(13, 10, '2023-11-13 14:20:00'),
(13, 15, '2023-11-13 15:10:00'),
(13, 20, '2023-11-13 16:25:00'),
(13, 25, '2023-11-13 17:40:00'),
(14, 6, '2023-11-14 22:20:00'),
(14, 8, '2023-11-14 23:05:00'),
(14, 12, '2023-11-15 00:20:00'),
(14, 16, '2023-11-15 01:35:00'),
(14, 20, '2023-11-15 02:50:00'),
(15, 1, '2023-11-15 09:45:00'),
(15, 3, '2023-11-15 10:20:00'),
(15, 4, '2023-11-15 11:35:00'),
(15, 7, '2023-11-15 12:50:00'),
(15, 10, '2023-11-15 14:05:00'),
(16, 8, '2023-11-16 14:30:00'),
(16, 12, '2023-11-16 15:45:00'),
(16, 16, '2023-11-16 17:00:00'),
(16, 20, '2023-11-16 18:15:00'),
(16, 24, '2023-11-16 19:30:00'),
(17, 1, '2023-11-17 11:20:00'),
(17, 5, '2023-11-17 12:35:00'),
(17, 9, '2023-11-17 13:50:00'),
(17, 13, '2023-11-17 15:05:00'),
(17, 17, '2023-11-17 16:20:00'),
(18, 14, '2023-11-18 15:30:00'),
(18, 18, '2023-11-18 16:45:00'),
(18, 22, '2023-11-18 18:00:00'),
(18, 26, '2023-11-18 19:15:00'),
(18, 30, '2023-11-18 20:30:00'),
(19, 5, '2023-11-19 17:15:00'),
(19, 10, '2023-11-19 18:30:00'),
(19, 15, '2023-11-19 19:45:00'),
(19, 20, '2023-11-19 21:00:00'),
(19, 25, '2023-11-19 22:15:00'),
(20, 9, '2023-11-20 12:10:00'),
(20, 14, '2023-11-20 13:25:00'),
(20, 19, '2023-11-20 14:40:00'),
(20, 24, '2023-11-20 15:55:00'),
(20, 29, '2023-11-20 17:10:00'),
(21, 14, '2023-11-21 16:25:00'),
(21, 19, '2023-11-21 17:40:00'),
(21, 24, '2023-11-21 18:55:00'),
(21, 29, '2023-11-21 20:10:00'),
(21, 34, '2023-11-21 21:25:00'),
(22, 2, '2023-11-22 21:00:00'),
(22, 7, '2023-11-22 22:15:00'),
(22, 12, '2023-11-22 23:30:00'),
(22, 17, '2023-11-23 00:45:00'),
(22, 22, '2023-11-23 02:00:00'),
(23, 1, '2023-11-23 10:45:00'),
(23, 6, '2023-11-23 12:00:00'),
(23, 11, '2023-11-23 13:15:00'),
(23, 16, '2023-11-23 14:30:00'),
(23, 21, '2023-11-23 15:45:00'),
(24, 9, '2023-11-24 08:50:00'),
(24, 14, '2023-11-24 10:05:00'),
(24, 19, '2023-11-24 11:20:00'),
(24, 24, '2023-11-24 12:35:00'),
(24, 29, '2023-11-24 13:50:00'),
(25, 5, '2023-11-25 19:05:00'),
(25, 10, '2023-11-25 20:20:00'),
(25, 15, '2023-11-25 21:35:00'),
(25, 20, '2023-11-25 22:50:00'),
(25, 25, '2023-11-26 00:05:00'),
(26, 34, '2023-11-26 08:20:00'),
(26, 39, '2023-11-26 09:35:00'),
(26, 44, '2023-11-26 10:50:00'),
(26, 49, '2023-11-26 12:05:00'),
(26, 54, '2023-11-26 13:20:00'),
(27, 4, '2023-11-27 13:30:00'),
(27, 9, '2023-11-27 14:45:00'),
(27, 14, '2023-11-27 16:00:00'),
(27, 19, '2023-11-27 17:15:00'),
(27, 24, '2023-11-27 18:30:00'),
(28, 10, '2023-11-28 15:40:00'),
(28, 15, '2023-11-28 16:55:00'),
(28, 20, '2023-11-28 18:10:00'),
(28, 25, '2023-11-28 19:25:00'),
(28, 30, '2023-11-28 20:40:00'),
(29, 13, '2023-11-29 17:00:00'),
(29, 18, '2023-11-29 18:15:00'),
(29, 23, '2023-11-29 19:30:00'),
(29, 28, '2023-11-29 20:45:00'),
(29, 33, '2023-11-29 22:00:00'),
(30, 30, '2023-11-30 20:10:00'),
(30, 35, '2023-11-30 21:25:00'),
(30, 40, '2023-11-30 22:40:00'),
(30, 45, '2023-11-30 23:55:00'),
(30, 50, '2023-12-01 01:10:00'),
(31, 2, '2023-12-01 11:10:00'),
(31, 7, '2023-12-01 12:25:00'),
(31, 12, '2023-12-01 13:40:00'),
(31, 17, '2023-12-01 14:55:00'),
(31, 22, '2023-12-01 16:10:00'),
(32, 7, '2023-12-02 14:25:00'),
(32, 12, '2023-12-02 15:40:00'),
(32, 17, '2023-12-02 16:55:00'),
(32, 22, '2023-12-02 18:10:00'),
(32, 27, '2023-12-02 19:25:00'),
(33, 14, '2023-12-03 09:50:00'),
(33, 19, '2023-12-03 11:05:00'),
(33, 24, '2023-12-03 12:20:00'),
(33, 29, '2023-12-03 13:35:00'),
(33, 34, '2023-12-03 14:50:00'),
(34, 49, '2023-12-04 07:10:00'),
(34, 54, '2023-12-04 08:25:00'),
(34, 59, '2023-12-04 09:40:00'),
(34, 4, '2023-12-04 10:55:00'),
(34, 9, '2023-12-04 12:10:00'),
(35, 23, '2023-12-05 18:20:00'),
(35, 28, '2023-12-05 19:35:00'),
(35, 33, '2023-12-05 20:50:00'),
(35, 38, '2023-12-05 22:05:00'),
(35, 43, '2023-12-05 23:20:00'),
(36, 9, '2023-12-06 09:30:00'),
(36, 14, '2023-12-06 10:45:00'),
(36, 19, '2023-12-06 12:00:00'),
(36, 24, '2023-12-06 13:15:00'),
(36, 29, '2023-12-06 14:30:00'),
(37, 1, '2023-12-07 12:35:00'),
(37, 6, '2023-12-07 13:50:00'),
(37, 11, '2023-12-07 15:05:00'),
(37, 16, '2023-12-07 16:20:00'),
(37, 21, '2023-12-07 17:35:00'),
(38, 10, '2023-12-08 16:00:00'),
(38, 15, '2023-12-08 17:15:00'),
(38, 20, '2023-12-08 18:30:00'),
(38, 25, '2023-12-08 19:45:00'),
(38, 30, '2023-12-08 21:00:00'),
(39, 16, '2023-12-09 11:10:00'),
(39, 21, '2023-12-09 12:25:00'),
(39, 26, '2023-12-09 13:40:00'),
(39, 31, '2023-12-09 14:55:00'),
(39, 36, '2023-12-09 16:10:00'),
(40, 6, '2023-12-10 15:20:00'),
(40, 11, '2023-12-10 16:35:00'),
(40, 16, '2023-12-10 17:50:00'),
(40, 21, '2023-12-10 19:05:00'),
(40, 26, '2023-12-10 20:20:00'),
(41, 3, '2023-12-11 20:30:00'),
(41, 8, '2023-12-11 21:45:00'),
(41, 13, '2023-12-11 23:00:00'),
(41, 18, '2023-12-12 00:15:00'),
(41, 23, '2023-12-12 01:30:00'),
(42, 6, '2023-12-12 13:40:00'),
(42, 11, '2023-12-12 14:55:00'),
(42, 16, '2023-12-12 16:10:00'),
(42, 21, '2023-12-12 17:25:00'),
(42, 26, '2023-12-12 18:40:00'),
(43, 24, '2023-12-13 17:10:00'),
(43, 29, '2023-12-13 18:25:00'),
(43, 34, '2023-12-13 19:40:00'),
(43, 39, '2023-12-13 20:55:00'),
(43, 44, '2023-12-13 22:10:00'),
(44, 1, '2023-12-14 19:15:00'),
(44, 6, '2023-12-14 20:30:00'),
(44, 11, '2023-12-14 21:45:00'),
(44, 16, '2023-12-14 23:00:00'),
(44, 21, '2023-12-15 00:15:00'),
(45, 28, '2023-12-15 14:30:00'),
(45, 33, '2023-12-15 15:45:00'),
(45, 38, '2023-12-15 17:00:00'),
(45, 43, '2023-12-15 18:15:00'),
(45, 48, '2023-12-15 19:30:00'),
(46, 3, '2023-12-16 09:55:00'),
(46, 8, '2023-12-16 11:10:00'),
(46, 13, '2023-12-16 12:25:00'),
(46, 18, '2023-12-16 13:40:00'),
(46, 23, '2023-12-16 14:55:00'),
(47, 9, '2023-12-17 12:10:00'),
(47, 14, '2023-12-17 13:25:00'),
(47, 19, '2023-12-17 14:40:00'),
(47, 24, '2023-12-17 15:55:00'),
(47, 29, '2023-12-17 17:10:00'),
(48, 18, '2023-12-18 16:25:00'),
(48, 23, '2023-12-18 17:40:00'),
(48, 28, '2023-12-18 18:55:00'),
(48, 33, '2023-12-18 20:10:00'),
(48, 38, '2023-12-18 21:25:00'),
(49, 34, '2023-12-19 08:40:00'),
(49, 39, '2023-12-19 09:55:00'),
(49, 44, '2023-12-19 11:10:00'),
(49, 49, '2023-12-19 12:25:00'),
(49, 54, '2023-12-19 13:40:00'),
(50, 14, '2023-12-20 15:05:00'),
(50, 19, '2023-12-20 16:20:00'),
(50, 24, '2023-12-20 17:35:00'),
(50, 29, '2023-12-20 18:50:00'),
(50, 34, '2023-12-20 20:05:00'),
(51, 9, '2023-12-21 11:20:00'),
(51, 14, '2023-12-21 12:35:00'),
(51, 19, '2023-12-21 13:50:00'),
(51, 24, '2023-12-21 15:05:00'),
(51, 29, '2023-12-21 16:20:00'),
(52, 2, '2023-12-22 17:35:00'),
(52, 7, '2023-12-22 18:50:00'),
(52, 12, '2023-12-22 20:05:00'),
(52, 17, '2023-12-22 21:20:00'),
(52, 22, '2023-12-22 22:35:00'),
(53, 10, '2023-12-23 13:25:00'),
(53, 15, '2023-12-23 14:40:00'),
(53, 20, '2023-12-23 15:55:00'),
(53, 25, '2023-12-23 17:10:00'),
(53, 30, '2023-12-23 18:25:00'),
(54, 13, '2023-12-24 21:05:00'),
(54, 18, '2023-12-24 22:20:00'),
(54, 23, '2023-12-24 23:35:00'),
(54, 28, '2023-12-25 00:50:00'),
(54, 33, '2023-12-25 02:05:00'),
(55, 14, '2023-12-25 10:30:00'),
(55, 19, '2023-12-25 11:45:00'),
(55, 24, '2023-12-25 13:00:00'),
(55, 29, '2023-12-25 14:15:00'),
(55, 34, '2023-12-25 15:30:00'),
(56, 23, '2023-12-26 14:30:00'),
(56, 28, '2023-12-26 15:45:00'),
(56, 33, '2023-12-26 17:00:00'),
(56, 38, '2023-12-26 18:15:00'),
(56, 43, '2023-12-26 19:30:00'),
(57, 1, '2023-12-27 12:00:00'),
(57, 6, '2023-12-27 13:15:00'),
(57, 11, '2023-12-27 14:30:00'),
(57, 16, '2023-12-27 15:45:00'),
(57, 21, '2023-12-27 17:00:00'),
(58, 10, '2023-12-28 16:20:00'),
(58, 15, '2023-12-28 17:35:00'),
(58, 20, '2023-12-28 18:50:00'),
(58, 25, '2023-12-28 20:05:00'),
(58, 30, '2023-12-28 21:20:00'),
(59, 9, '2023-12-29 19:35:00'),
(59, 14, '2023-12-29 20:50:00'),
(59, 19, '2023-12-29 22:05:00'),
(59, 24, '2023-12-29 23:20:00'),
(59, 29, '2023-12-30 00:35:00'),
(60, 10, '2023-12-30 10:50:00'),
(60, 15, '2023-12-30 12:05:00'),
(60, 20, '2023-12-30 13:20:00'),
(60, 25, '2023-12-30 14:35:00'),
(60, 30, '2023-12-30 15:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `user_id` int NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `bio` text,
  `avatar_url` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`user_id`, `display_name`, `bio`, `avatar_url`, `updated_at`) VALUES
(1, 'Alex Johnson', 'Software engineer passionate about AI and machine learning. Coffee enthusiast ☕', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Alex', '2023-06-20 14:25:00'),
(2, 'Mariah Lee', 'Digital nomad and photographer. Currently exploring Southeast Asia 📸', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mariah', '2023-08-15 11:40:00'),
(3, 'Sam Chen', 'Full-stack developer | Open source contributor | Tech blogger', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sam', '2023-09-10 08:20:00'),
(4, 'Emily Wanderlust', 'Travel blogger visiting 30+ countries and counting! ✈️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Emily', '2023-07-22 19:30:00'),
(5, 'David Beats', 'Music producer and guitarist. Always looking for new sounds 🎵', 'https://api.dicebear.com/7.x/avataaars/svg?seed=David', '2023-10-05 16:45:00'),
(6, 'Sarah Page', 'Bibliophile and literary critic. Currently reading: Klara and the Sun', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah', '2023-08-30 10:15:00'),
(7, 'Mike Player', 'Professional gamer and streamer. Specializing in FPS games 🎮', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mike', '2023-09-25 22:10:00'),
(8, 'Lisa Strong', 'Certified personal trainer and nutrition specialist 💪', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Lisa', '2023-07-15 18:20:00'),
(9, 'Jamie Cook', 'Food blogger and amateur chef. Love experimenting with fusion cuisine 🍳', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Jamie', '2023-10-18 12:35:00'),
(10, 'Chloe Canvas', 'Digital artist and illustrator. Commission open! 🎨', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Chloe', '2023-08-10 09:45:00'),
(11, 'Jane Admin', 'Subspace administrator. Here to help keep the community safe!', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Jane', '2023-11-01 10:00:00'),
(12, 'Mark System', 'System administrator and community moderator', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mark', '2023-11-05 14:30:00'),
(13, 'Ryan Tech', 'Tech reviewer and gadget enthusiast. Always on the latest tech!', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ryan', '2023-09-28 17:20:00'),
(14, 'Olivia Green', 'Environmental activist and hiking guide 🌿', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Olivia', '2023-08-12 13:25:00'),
(15, 'Tom Screen', 'Film critic and movie buff. Watched 500+ films this year! 🎬', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Tom', '2023-10-22 20:45:00'),
(16, 'Marcus Runner', 'Marathon runner and fitness coach. Always chasing new PRs! 🏃‍♂️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Marcus', '2023-08-05 07:30:00'),
(17, 'Sophia Data', 'Data scientist exploring the world of Python and machine learning', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sophia', '2023-09-15 11:20:00'),
(18, 'Henry Green', 'Plant enthusiast and urban gardener. My apartment is a jungle 🌱', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Henry', '2023-07-28 14:45:00'),
(19, 'Grace Keys', 'Piano teacher and classical music lover. Bach enthusiast 🎹', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Grace', '2023-10-10 09:15:00'),
(20, 'Luna Paw', 'Animal rescuer and dog trainer. All dogs deserve love and training 🐾', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Luna', '2023-08-20 16:30:00'),
(21, 'Ethan Thread', 'Sustainable fashion designer using recycled materials 👕', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ethan', '2023-09-05 13:40:00'),
(22, 'Aurora Sky', 'Nature photographer chasing storms and Northern Lights 🌌', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Aurora', '2023-07-10 18:25:00'),
(23, 'Dr. Leo Scholar', 'PhD candidate in Astrophysics. Studying black holes and dark matter', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Leo', '2023-10-25 15:50:00'),
(24, 'Olivia Bake', 'Artisanal baker specializing in sourdough and pastries 🥖', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Olivia2', '2023-08-08 06:45:00'),
(25, 'Vinyl Max', 'Record collector with over 500 vinyls. Jazz and blues specialist', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Max', '2023-09-30 19:20:00'),
(26, 'Yoga Maya', 'Yoga instructor and meditation guide. Finding peace in movement 🧘‍♀️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Maya', '2023-07-18 08:15:00'),
(27, 'Linguist Ken', 'Polyglot speaking 5 languages. Currently learning Japanese 🇯🇵', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ken', '2023-10-12 12:30:00'),
(28, 'Lens Carter', 'Professional photographer specializing in urban landscapes 📷', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Carter', '2023-08-25 17:40:00'),
(29, 'Crypto Finn', 'Blockchain developer and cryptocurrency investor 📈', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Finn', '2023-09-22 14:55:00'),
(30, 'Knit Nora', 'Knitting enthusiast creating custom sweaters and scarves 🧶', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Nora', '2023-07-30 20:10:00'),
(31, 'Digital Noah', 'Remote software developer living the digital nomad life 🌍', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Noah', '2023-10-08 11:25:00'),
(32, 'PC Build Liam', 'Gaming PC builder and hardware reviewer. RGB everything! 💻', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Liam', '2023-08-14 13:50:00'),
(33, 'Urban Eva', 'Balcony gardener growing vegetables in the city 🌶️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Eva', '2023-09-17 09:05:00'),
(34, 'Zen Oliver', 'Meditation teacher and mindfulness coach. Finding calm daily ☮️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Oliver', '2023-07-05 05:30:00'),
(35, 'Academic Zoe', 'Applying for PhD programs in Neuroscience. Brain enthusiast 🧠', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Zoe', '2023-10-30 16:45:00'),
(36, 'Coffee Leo', 'Latte art champion and coffee shop reviewer. Always caffeinated ☕', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Leo2', '2023-08-28 08:20:00'),
(37, 'Code Ruby', 'Learning to code at 40. Career change adventures 💻', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ruby', '2023-09-12 10:35:00'),
(38, 'Miniature Jack', 'Tabletop gaming miniature painter. Dragons and warriors! 🐉', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Jack', '2023-07-24 15:50:00'),
(39, 'Runner Chloe', '10K runner training for a half marathon. Love endorphins! 🏃‍♀️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Chloe2', '2023-10-15 07:15:00'),
(40, 'Author Ben', 'Historical fiction writer researching 1920s Paris 📚', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ben', '2023-08-03 14:40:00'),
(41, 'Smart Home Kai', 'Home automation enthusiast. My house runs on voice commands 🏠', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Kai', '2023-09-27 18:05:00'),
(42, 'Book Club Mia', 'Organizing monthly book discussions. Literature brings us together 📖', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mia', '2023-07-14 12:30:00'),
(43, 'Clay Emma', 'Pottery beginner making wonky but lovable bowls and mugs 🏺', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Emma', '2023-10-20 16:55:00'),
(44, 'Podcast Lucas', 'Podcast host discussing technology and its impact on society 🎙️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Lucas', '2023-08-17 19:20:00'),
(45, 'Urbex Alex', 'Urban explorer photographing abandoned places. Beauty in decay 🏚️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Alex2', '2023-09-08 13:45:00'),
(46, 'UX Harper', 'Recent UX design graduate looking for opportunities in tech 🎨', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Harper', '2023-07-21 11:10:00'),
(47, 'Spice Mason', 'Hot sauce maker experimenting with ghost peppers and habaneros 🌶️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mason', '2023-10-03 14:25:00'),
(48, 'Builder Owen', 'DIY enthusiast building a treehouse for my kids. Childhood dreams 🌳', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Owen', '2023-08-22 16:50:00'),
(49, 'Meditate Ava', 'Recently returned from a 10-day silent meditation retreat 🧘', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ava', '2023-09-19 06:15:00'),
(50, 'Eco Logan', 'YouTube creator focusing on sustainable living and zero waste 🌎', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Logan', '2023-07-07 14:40:00'),
(51, 'Brew Ella', 'Kombucha brewer experimenting with exotic tea blends 🍵', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ella', '2023-10-28 10:05:00'),
(52, 'Surf Luke', 'Learning to surf at 35. More wipeouts than waves so far! 🏄‍♂️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Luke', '2023-08-11 17:30:00'),
(53, 'Design Scarlett', 'Freelance graphic designer launching my own business 💼', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Scarlett', '2023-09-14 12:55:00'),
(54, 'Star Theo', 'Amateur astronomer photographing planets and nebulae ✨', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Theo', '2023-07-26 21:20:00'),
(55, 'Garden Lily', 'Starting a community garden project in our neighborhood 🥕', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Lily', '2023-10-07 09:45:00'),
(56, 'Vintage James', 'Restoring vintage typewriters and mechanical keyboards ⌨️', 'https://api.dicebear.com/7.x/avataaars/svg?seed=James', '2023-08-29 13:10:00'),
(57, 'App Charlotte', 'Mindfulness app developer helping people find peace digitally 📱', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Charlotte', '2023-09-23 11:35:00'),
(58, 'Wood Daniel', 'Learning traditional Japanese woodworking techniques 🔨', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Daniel', '2023-07-12 15:00:00'),
(59, 'Cat Amelia', 'Cat rescue volunteer and foster parent. Currently with 2 cats 🐱', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Amelia', '2023-10-13 18:25:00'),
(60, 'Journal Sofia', 'Bullet journal enthusiast organizing life with colorful spreads 📓', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sofia', '2023-08-19 10:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `spaces`
--

CREATE TABLE `spaces` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `spaces`
--

INSERT INTO `spaces` (`id`, `user_id`, `title`, `subject`, `description`, `is_hidden`, `created_at`, `updated_at`) VALUES
(1, 1, 'Machine Learning Enthusiasts', 'AI & ML Discussions', 'A space for discussing machine learning algorithms, frameworks, and projects. Share your ML journey!', 0, '2023-10-01 09:00:00', NULL),
(2, 2, 'Digital Nomad Life', 'Remote Work & Travel', 'Share tips, experiences, and recommendations for working remotely while traveling the world.', 0, '2023-10-02 10:15:00', NULL),
(3, 3, 'Web Development Hub', 'Frontend & Backend', 'Discuss modern web development frameworks, best practices, and share project ideas.', 0, '2023-10-03 11:30:00', NULL),
(4, 4, 'World Travelers', 'Travel Stories & Tips', 'Share your travel experiences, photos, and recommendations from around the globe.', 0, '2023-10-04 12:45:00', NULL),
(5, 5, 'Music Production Studio', 'Audio Engineering & Composition', 'Discuss music production techniques, software, and share your latest tracks.', 0, '2023-10-05 14:00:00', NULL),
(6, 6, 'Bookworms United', 'Literature & Reading', 'Share book recommendations, reviews, and discuss your favorite authors and genres.', 0, '2023-10-06 15:15:00', NULL),
(7, 7, 'Gaming Arena', 'Video Games & Esports', 'Discuss the latest games, share gaming tips, and organize multiplayer sessions.', 0, '2023-10-07 16:30:00', NULL),
(8, 8, 'Fitness & Wellness', 'Health & Exercise', 'Share workout routines, nutrition tips, and wellness practices for a healthy lifestyle.', 0, '2023-10-08 17:45:00', NULL),
(9, 9, 'Foodie Adventures', 'Cooking & Recipes', 'Share recipes, cooking techniques, and restaurant recommendations from around the world.', 0, '2023-10-09 19:00:00', NULL),
(10, 10, 'Digital Art Gallery', 'Art & Illustration', 'Showcase your digital art, discuss techniques, and share resources for artists.', 0, '2023-10-10 20:15:00', NULL),
(11, 11, 'Community Guidelines', 'Rules & Moderation', 'Official space for community guidelines, announcements, and moderation discussions.', 0, '2023-10-11 21:30:00', NULL),
(12, 12, 'Tech Support', 'Help & Troubleshooting', 'Get help with technical issues, platform questions, and bug reports.', 0, '2023-10-12 22:45:00', NULL),
(13, 13, 'Gadget Reviewers', 'Tech Reviews & News', 'Discuss the latest gadgets, share reviews, and tech industry news.', 0, '2023-10-13 09:00:00', NULL),
(14, 14, 'Eco Warriors', 'Sustainability & Environment', 'Discuss environmental issues, sustainable living, and conservation efforts.', 0, '2023-10-14 10:15:00', NULL),
(15, 15, 'Film Buffs', 'Movies & Cinema', 'Discuss films, directors, cinematography, and share movie recommendations.', 0, '2023-10-15 11:30:00', NULL),
(16, 16, 'Running Club', 'Marathon Training', 'Share running tips, training plans, and race experiences for runners of all levels.', 0, '2023-10-16 12:45:00', NULL),
(17, 17, 'Data Science Lab', 'Data Analysis & Python', 'Discuss data science projects, Python libraries, and machine learning applications.', 0, '2023-10-17 13:00:00', NULL),
(18, 18, 'Plant Parents', 'Gardening & Houseplants', 'Share plant care tips, propagation techniques, and showcase your green friends.', 0, '2023-10-18 14:15:00', NULL),
(19, 19, 'Music Lessons', 'Instrument Learning', 'Share tips for learning instruments, music theory, and practice routines.', 0, '2023-10-19 15:30:00', NULL),
(20, 20, 'Animal Lovers', 'Pets & Animal Rescue', 'Share pet stories, training tips, and discuss animal welfare and rescue efforts.', 0, '2023-10-20 16:45:00', NULL),
(21, 21, 'Sustainable Fashion', 'Eco-Friendly Clothing', 'Discuss sustainable fashion brands, upcycling, and ethical clothing choices.', 0, '2023-10-21 17:00:00', NULL),
(22, 22, 'Nature Photography', 'Outdoor Photography', 'Share nature photography tips, locations, and showcase your outdoor shots.', 0, '2023-10-22 18:15:00', NULL),
(23, 23, 'Academic Research', 'Science & Academia', 'Discuss research methods, academic writing, and share publications.', 0, '2023-10-23 19:30:00', NULL),
(24, 24, 'Artisan Bakers', 'Baking & Pastry', 'Share baking recipes, techniques, and troubleshoot baking challenges.', 0, '2023-10-24 20:45:00', NULL),
(25, 25, 'Vinyl Collectors', 'Record Collection', 'Discuss vinyl records, turntables, and share rare finds from your collection.', 0, '2023-10-25 21:00:00', NULL),
(26, 26, 'Yoga Community', 'Mindfulness & Movement', 'Share yoga sequences, meditation techniques, and wellness practices.', 0, '2023-10-26 22:15:00', NULL),
(27, 27, 'Language Exchange', 'Languages & Linguistics', 'Practice languages, share learning resources, and discuss linguistics.', 0, '2023-10-27 23:30:00', NULL),
(28, 28, 'Photography Studio', 'Professional Photography', 'Discuss photography equipment, editing techniques, and professional work.', 0, '2023-10-28 08:45:00', NULL),
(29, 29, 'Crypto Investors', 'Blockchain & Cryptocurrency', 'Discuss blockchain technology, cryptocurrency investments, and market trends.', 0, '2023-10-29 09:00:00', NULL),
(30, 30, 'Knitting Circle', 'Fiber Arts & Crafts', 'Share knitting patterns, techniques, and showcase your handmade creations.', 0, '2023-10-30 10:15:00', NULL),
(31, 31, 'Remote Workers', 'Digital Nomad Tips', 'Discuss remote work tools, time management, and balancing work with travel.', 0, '2023-10-31 11:30:00', NULL),
(32, 32, 'PC Builders', 'Computer Hardware', 'Discuss PC components, building tips, and troubleshooting hardware issues.', 0, '2023-11-01 12:45:00', NULL),
(33, 33, 'Urban Gardeners', 'City Farming', 'Share tips for growing food in small spaces, balcony gardens, and container gardening.', 0, '2023-11-02 13:00:00', NULL),
(34, 34, 'Meditation Group', 'Mindfulness Practice', 'Discuss meditation techniques, mindfulness apps, and share meditation experiences.', 0, '2023-11-03 14:15:00', NULL),
(35, 35, 'PhD Candidates', 'Graduate Studies', 'Share PhD experiences, research challenges, and academic career advice.', 0, '2023-11-04 15:30:00', NULL),
(36, 36, 'Coffee Connoisseurs', 'Specialty Coffee', 'Discuss coffee brewing methods, bean origins, and share cafe recommendations.', 0, '2023-11-05 16:45:00', NULL),
(37, 37, 'Career Changers', 'Adult Learning', 'Share experiences of changing careers, learning new skills as an adult.', 0, '2023-11-06 17:00:00', NULL),
(38, 38, 'Miniature Painters', 'Tabletop Gaming', 'Discuss miniature painting techniques, color theory, and showcase your work.', 0, '2023-11-07 18:15:00', NULL),
(39, 39, 'Running Beginners', 'Couch to 5K', 'Support for new runners, share progress, and training tips for beginners.', 0, '2023-11-08 19:30:00', NULL),
(40, 40, 'Writers\' Workshop', 'Creative Writing', 'Share writing tips, works in progress, and discuss the writing process.', 0, '2023-11-09 20:45:00', NULL),
(41, 41, 'Smart Home Tech', 'Home Automation', 'Discuss smart home devices, automation routines, and IoT projects.', 0, '2023-11-10 21:00:00', NULL),
(42, 42, 'Book Club', 'Monthly Readings', 'Organize book discussions, share reading lists, and literary analysis.', 0, '2023-11-11 22:15:00', NULL),
(43, 43, 'Pottery Studio', 'Ceramics & Clay', 'Share pottery techniques, kiln experiences, and showcase your ceramic work.', 0, '2023-11-12 23:30:00', NULL),
(44, 44, 'Podcast Creators', 'Audio Content', 'Discuss podcast equipment, editing software, and share your episodes.', 0, '2023-11-13 08:45:00', NULL),
(45, 45, 'Urban Exploration', 'Abandoned Places', 'Share urbex locations, safety tips, and photography from abandoned sites.', 0, '2023-11-14 09:00:00', NULL),
(46, 46, 'UX Designers', 'User Experience', 'Discuss UX principles, design tools, and share portfolio feedback.', 0, '2023-11-15 10:15:00', NULL),
(47, 47, 'Hot Sauce Makers', 'Fermentation & Spice', 'Share hot sauce recipes, fermentation techniques, and pepper growing tips.', 0, '2023-11-16 11:30:00', NULL),
(48, 48, 'DIY Builders', 'Home Projects', 'Share DIY project plans, building tips, and showcase your creations.', 0, '2023-11-17 12:45:00', NULL),
(49, 49, 'Meditation Retreats', 'Silent Practice', 'Discuss meditation retreat experiences, techniques, and spiritual growth.', 0, '2023-11-18 13:00:00', NULL),
(50, 50, 'Sustainable Living', 'Zero Waste Lifestyle', 'Share tips for reducing waste, eco-friendly products, and sustainable habits.', 0, '2023-11-19 14:15:00', NULL),
(51, 51, 'Fermentation Station', 'Kombucha & More', 'Discuss fermentation techniques, SCOBY care, and share flavor experiments.', 0, '2023-11-20 15:30:00', NULL),
(52, 52, 'Surfing Community', 'Wave Riding', 'Share surfing tips, spot recommendations, and wave forecasting resources.', 0, '2023-11-21 16:45:00', NULL),
(53, 53, 'Freelance Design', 'Graphic Design Business', 'Discuss freelance rates, client management, and building a design portfolio.', 0, '2023-11-22 17:00:00', NULL),
(54, 54, 'Astronomy Club', 'Stargazing & Telescopes', 'Discuss astronomy equipment, observation tips, and celestial events.', 0, '2023-11-23 18:15:00', NULL),
(55, 55, 'Community Gardens', 'Urban Agriculture', 'Organize community garden projects, share growing tips, and seed exchanges.', 0, '2023-11-24 19:30:00', NULL),
(56, 56, 'Vintage Restoration', 'Antique Repair', 'Discuss restoration techniques, sourcing parts, and showcase restored items.', 0, '2023-11-25 20:45:00', NULL),
(57, 57, 'App Development', 'Mobile Apps', 'Discuss app development frameworks, UI/UX design, and app store optimization.', 0, '2023-11-26 21:00:00', NULL),
(58, 58, 'Woodworking Shop', 'Traditional Craft', 'Share woodworking projects, tool recommendations, and joinery techniques.', 0, '2023-11-27 22:15:00', NULL),
(59, 59, 'Cat Rescue Network', 'Feline Welfare', 'Organize cat rescue efforts, share fostering tips, and adoption events.', 0, '2023-11-28 23:30:00', NULL),
(60, 60, 'Planning & Journaling', 'Bullet Journal Methods', 'Share bullet journal layouts, planning techniques, and productivity tips.', 0, '2023-11-29 08:45:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'alexj', 'alex.johnson@example.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-15 10:30:00', '2023-06-20 14:25:00'),
(2, 'mariah_lee', 'mariah.lee@example.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-10 09:15:00', '2023-08-15 11:40:00'),
(3, 'coding_wizard', 'sam.chen@tech.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-05 16:45:00', '2023-09-10 08:20:00'),
(4, 'travel_bug', 'emily.travels@nomad.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-28 12:20:00', '2023-07-22 19:30:00'),
(5, 'music_lover', 'david.music@beat.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-12 14:10:00', '2023-10-05 16:45:00'),
(6, 'bookworm', 'sarah.reads@library.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-25 11:05:00', '2023-08-30 10:15:00'),
(7, 'gamer_4life', 'mike.games@play.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-18 20:30:00', '2023-09-25 22:10:00'),
(8, 'fitness_guru', 'lisa.fit@health.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-10 07:45:00', '2023-07-15 18:20:00'),
(9, 'foodie_expert', 'jamie.food@chef.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-01 13:25:00', '2023-10-18 12:35:00'),
(10, 'art_soul', 'chloe.art@canvas.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-15 15:50:00', '2023-08-10 09:45:00'),
(11, 'admin_jane', 'jane.admin@subspace.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'admin', '2023-01-01 08:00:00', '2023-11-01 10:00:00'),
(12, 'admin_mark', 'mark.sys@subspace.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'admin', '2023-01-01 09:00:00', '2023-11-05 14:30:00'),
(13, 'tech_guru', 'ryan.tech@geek.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-22 11:40:00', '2023-09-28 17:20:00'),
(14, 'nature_lover', 'olivia.nature@green.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-05 10:15:00', '2023-08-12 13:25:00'),
(15, 'movie_buff', 'tom.cinema@film.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-08 19:20:00', '2023-10-22 20:45:00'),
(16, 'marathon_man', 'marcus.run@fitness.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-18 07:15:00', '2023-08-05 07:30:00'),
(17, 'data_sophia', 'sophia.data@python.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-10 10:45:00', '2023-09-15 11:20:00'),
(18, 'plant_henry', 'henry.green@plants.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-22 13:20:00', '2023-07-28 14:45:00'),
(19, 'grace_keys', 'grace.music@piano.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-05 08:30:00', '2023-10-10 09:15:00'),
(20, 'luna_paw', 'luna.dog@rescue.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-28 15:05:00', '2023-08-20 16:30:00'),
(21, 'ethan_thread', 'ethan.fashion@eco.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-15 12:10:00', '2023-09-05 13:40:00'),
(22, 'aurora_sky', 'aurora.photo@nature.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-05 17:00:00', '2023-07-10 18:25:00'),
(23, 'dr_leo', 'leo.scholar@research.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-20 14:25:00', '2023-10-25 15:50:00'),
(24, 'olivia_bake', 'olivia.bake@bread.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-12 05:20:00', '2023-08-08 06:45:00'),
(25, 'vinyl_max', 'max.records@music.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-25 18:35:00', '2023-09-30 19:20:00'),
(26, 'yoga_maya', 'maya.yoga@mindful.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-18 07:40:00', '2023-07-18 08:15:00'),
(27, 'linguist_ken', 'ken.lang@polyglot.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-15 11:15:00', '2023-10-12 12:30:00'),
(28, 'lens_carter', 'carter.photo@studio.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-22 16:15:00', '2023-08-25 17:40:00'),
(29, 'crypto_finn', 'finn.crypto@blockchain.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-28 13:30:00', '2023-09-22 14:55:00'),
(30, 'knit_nora', 'nora.knit@yarn.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-25 18:45:00', '2023-07-30 20:10:00'),
(31, 'digital_noah', 'noah.remote@nomad.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-10 10:05:00', '2023-10-08 11:25:00'),
(32, 'pc_liam', 'liam.pc@build.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-08 12:30:00', '2023-08-14 13:50:00'),
(33, 'urban_eva', 'eva.garden@urban.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-12 08:20:00', '2023-09-17 09:05:00'),
(34, 'zen_oliver', 'oliver.meditate@zen.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-08 04:05:00', '2023-07-05 05:30:00'),
(35, 'academic_zoe', 'zoe.phd@research.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-25 15:10:00', '2023-10-30 16:45:00'),
(36, 'coffee_leo', 'leo.coffee@brew.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-14 07:25:00', '2023-08-28 08:20:00'),
(37, 'code_ruby', 'ruby.code@learn.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-07 09:40:00', '2023-09-12 10:35:00'),
(38, 'miniature_jack', 'jack.miniature@painting.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-20 14:15:00', '2023-07-24 15:50:00'),
(39, 'runner_chloe', 'chloe.run@fitness.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-18 06:30:00', '2023-10-15 07:15:00'),
(40, 'author_ben', 'ben.write@novel.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-03 13:05:00', '2023-08-03 14:40:00'),
(41, 'smart_home_kai', 'kai.smart@home.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-30 16:50:00', '2023-09-27 18:05:00'),
(42, 'book_club_mia', 'mia.book@club.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-12 11:00:00', '2023-07-14 12:30:00'),
(43, 'clay_emma', 'emma.pottery@clay.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-22 15:20:00', '2023-10-20 16:55:00'),
(44, 'podcast_lucas', 'lucas.podcast@audio.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-16 17:35:00', '2023-08-17 19:20:00'),
(45, 'urbex_alex', 'alex.urbex@explore.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-03 12:05:00', '2023-09-08 13:45:00'),
(46, 'ux_harper', 'harper.ux@design.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-17 09:50:00', '2023-07-21 11:10:00'),
(47, 'spice_mason', 'mason.spice@hot.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-07 13:15:00', '2023-10-03 14:25:00'),
(48, 'builder_owen', 'owen.build@diy.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-20 15:25:00', '2023-08-22 16:50:00'),
(49, 'meditate_ava', 'ava.meditate@retreat.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-14 05:40:00', '2023-09-19 06:15:00'),
(50, 'eco_logan', 'logan.eco@sustainable.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-03 13:10:00', '2023-07-07 14:40:00'),
(51, 'brew_ella', 'ella.brew@kombucha.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-28 09:05:00', '2023-10-28 10:05:00'),
(52, 'surf_luke', 'luke.surf@wave.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-07 16:00:00', '2023-08-11 17:30:00'),
(53, 'design_scarlett', 'scarlett.design@graphic.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-09 11:20:00', '2023-09-14 12:55:00'),
(54, 'star_theo', 'theo.star@astronomy.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-24 19:45:00', '2023-07-26 21:20:00'),
(55, 'garden_lily', 'lily.garden@community.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-13 08:15:00', '2023-10-07 09:45:00'),
(56, 'vintage_james', 'james.vintage@restore.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-26 12:05:00', '2023-08-29 13:10:00'),
(57, 'app_charlotte', 'charlotte.app@mindful.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-03-24 10:10:00', '2023-09-23 11:35:00'),
(58, 'wood_daniel', 'daniel.wood@traditional.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-01-07 13:50:00', '2023-07-12 15:00:00'),
(59, 'cat_amelia', 'amelia.cat@rescue.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-04-17 17:00:00', '2023-10-13 18:25:00'),
(60, 'journal_sofia', 'sofia.journal@plan.com', '$2y$10$rNQrL/HAbHjIwJ9E5FcVcOCXqsdVCHNWUDtPdnS8MruGvW3plRKxW', 'user', '2023-02-23 09:25:00', '2023-08-19 10:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_blocks`
--

CREATE TABLE `user_blocks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `blocked_by_admin_id` int NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `blocked_until` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `revoked_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_blocks`
--

INSERT INTO `user_blocks` (`id`, `user_id`, `blocked_by_admin_id`, `reason`, `blocked_until`, `created_at`, `revoked_at`) VALUES
(1, 7, 11, 'Spamming promotional content in comments', '2023-12-01 00:00:00', '2023-10-15 14:30:00', NULL),
(2, 13, 12, 'Multiple inappropriate posts violating community guidelines', '2024-01-01 00:00:00', '2023-10-20 09:15:00', NULL),
(3, 5, 11, 'Temporary suspension for heated arguments', '2023-11-01 00:00:00', '2023-10-10 16:45:00', '2023-10-25 11:20:00'),
(4, 25, 12, 'Sharing copyrighted material without permission', '2023-12-15 00:00:00', '2023-11-05 10:30:00', NULL),
(5, 38, 11, 'Harassing other users in private messages', '2024-02-01 00:00:00', '2023-11-10 14:45:00', NULL),
(6, 52, 12, 'Posting fake news and misinformation', '2023-12-01 00:00:00', '2023-11-15 09:20:00', '2023-11-20 16:30:00'),
(7, 17, 11, 'Creating multiple spam accounts', '2024-03-01 00:00:00', '2023-11-20 11:10:00', NULL),
(8, 43, 12, 'Plagiarizing content from other creators', '2023-12-31 00:00:00', '2023-11-25 15:40:00', NULL),
(9, 29, 11, 'Promoting pyramid schemes', '2023-12-10 00:00:00', '2023-11-30 13:25:00', '2023-12-05 10:15:00'),
(10, 56, 12, 'Sharing personal information of other users', '2024-01-15 00:00:00', '2023-12-05 17:50:00', NULL),
(11, 9, 11, 'Excessive self-promotion in comments', '2023-12-20 00:00:00', '2023-12-10 08:30:00', NULL),
(12, 34, 12, 'Creating fake giveaways to collect user data', '2024-02-15 00:00:00', '2023-12-15 12:45:00', NULL),
(13, 48, 11, 'Posting offensive content in public spaces', '2024-01-31 00:00:00', '2023-12-20 16:20:00', NULL),
(14, 21, 12, 'Manipulating voting system with multiple accounts', '2024-01-10 00:00:00', '2023-12-25 10:05:00', '2023-12-30 14:50:00'),
(15, 60, 11, 'Posting inappropriate content in family-friendly spaces', '2024-02-28 00:00:00', '2023-12-30 19:35:00', NULL),
(16, 33, 12, 'Circumventing previous block with new account', '2024-04-01 00:00:00', '2024-01-04 11:15:00', NULL),
(17, 46, 11, 'Sending unsolicited commercial messages', '2024-02-10 00:00:00', '2024-01-09 14:40:00', NULL),
(18, 19, 12, 'Engaging in hate speech against community members', '2024-03-15 00:00:00', '2024-01-14 09:25:00', NULL),
(19, 55, 11, 'Creating duplicate posts to manipulate algorithm', '2024-01-31 00:00:00', '2024-01-19 16:50:00', '2024-01-24 13:30:00'),
(20, 27, 12, 'Sharing pirated software and media', '2024-02-29 00:00:00', '2024-01-24 12:05:00', NULL),
(21, 40, 11, 'Engaging in coordinated harassment campaign', '2024-04-15 00:00:00', '2024-01-29 15:20:00', NULL),
(22, 14, 12, 'Posting graphic content without warnings', '2024-02-14 00:00:00', '2024-02-03 10:45:00', NULL),
(23, 51, 11, 'Creating fake customer reviews for products', '2024-03-10 00:00:00', '2024-02-08 13:10:00', '2024-02-13 11:25:00'),
(24, 22, 12, 'Posting dangerous misinformation about health', '2024-05-01 00:00:00', '2024-02-13 17:35:00', NULL),
(25, 45, 11, 'Stealing and reposting original artwork', '2024-03-31 00:00:00', '2024-02-18 08:50:00', NULL),
(26, 32, 12, 'Engaging in price gouging during crisis', '2024-04-10 00:00:00', '2024-02-23 14:05:00', NULL),
(27, 58, 11, 'Creating fake emergency fundraisers', '2024-06-01 00:00:00', '2024-02-28 11:20:00', NULL),
(28, 36, 12, 'Posting conspiracy theories as facts', '2024-03-20 00:00:00', '2024-03-04 16:45:00', '2024-03-09 09:30:00'),
(29, 49, 11, 'Selling prohibited items on platform', '2024-05-15 00:00:00', '2024-03-09 13:10:00', NULL),
(30, 23, 12, 'Sharing exam answers and academic dishonesty', '2024-04-05 00:00:00', '2024-03-14 10:25:00', NULL),
(31, 57, 11, 'Creating bot accounts to inflate engagement', '2024-07-01 00:00:00', '2024-03-19 15:40:00', NULL),
(32, 30, 12, 'Posting violent threats against other users', '2024-05-31 00:00:00', '2024-03-24 12:55:00', NULL),
(33, 41, 11, 'Running fraudulent investment schemes', '2024-06-15 00:00:00', '2024-03-29 09:10:00', NULL),
(34, 26, 12, 'Sharing private conversations without consent', '2024-04-30 00:00:00', '2024-04-03 14:25:00', '2024-04-08 11:40:00'),
(35, 53, 11, 'Creating fake business listings', '2024-08-01 00:00:00', '2024-04-08 17:50:00', NULL),
(36, 37, 12, 'Posting extremist political content', '2024-07-15 00:00:00', '2024-04-13 10:15:00', NULL),
(37, 20, 11, 'Selling fake event tickets', '2024-05-20 00:00:00', '2024-04-18 13:30:00', NULL),
(38, 54, 12, 'Creating fake celebrity accounts', '2024-09-01 00:00:00', '2024-04-23 08:45:00', NULL),
(39, 31, 11, 'Posting fabricated news stories', '2024-06-30 00:00:00', '2024-04-28 16:00:00', '2024-05-03 12:15:00'),
(40, 44, 12, 'Sharing malware disguised as useful software', '2024-10-01 00:00:00', '2024-05-03 11:25:00', NULL),
(41, 18, 11, 'Creating fake product reviews for compensation', '2024-08-15 00:00:00', '2024-05-08 14:40:00', NULL),
(42, 59, 12, 'Posting animal cruelty content', '2024-12-01 00:00:00', '2024-05-13 09:55:00', NULL),
(43, 35, 11, 'Sharing exam papers before test dates', '2024-07-31 00:00:00', '2024-05-18 17:10:00', NULL),
(44, 28, 12, 'Creating fake customer support accounts', '2024-09-15 00:00:00', '2024-05-23 12:25:00', '2024-05-28 10:40:00'),
(45, 42, 11, 'Posting revenge porn content', '2025-01-01 00:00:00', '2024-05-28 15:50:00', NULL),
(46, 47, 12, 'Sharing fake government documents', '2024-11-01 00:00:00', '2024-06-02 08:05:00', NULL),
(47, 24, 11, 'Creating fake rental listings to scam deposits', '2024-10-15 00:00:00', '2024-06-07 13:20:00', NULL),
(48, 39, 12, 'Posting discriminatory hiring practices', '2025-02-01 00:00:00', '2024-06-12 10:35:00', NULL),
(49, 50, 11, 'Sharing fake scientific studies', '2024-12-15 00:00:00', '2024-06-17 17:50:00', '2024-06-22 14:05:00'),
(50, 16, 12, 'Creating fake emergency alerts', '2025-03-01 00:00:00', '2024-06-22 11:10:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_posts_created_at` (`created_at`),
  ADD KEY `ix_posts_user_created` (`user_id`,`created_at`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_post_comments_post_created` (`post_id`,`created_at`),
  ADD KEY `ix_post_comments_user` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`post_id`,`user_id`),
  ADD KEY `ix_post_likes_user` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `spaces`
--
ALTER TABLE `spaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_spaces_created_at` (`created_at`),
  ADD KEY `ix_spaces_user_created` (`user_id`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- Indexes for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_user_blocks_user` (`user_id`,`created_at`),
  ADD KEY `fk_user_blocks_admin` (`blocked_by_admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `spaces`
--
ALTER TABLE `spaces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `user_blocks`
--
ALTER TABLE `user_blocks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `fk_post_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `fk_post_likes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spaces`
--
ALTER TABLE `spaces`
  ADD CONSTRAINT `fk_spaces_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD CONSTRAINT `fk_user_blocks_admin` FOREIGN KEY (`blocked_by_admin_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_user_blocks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;