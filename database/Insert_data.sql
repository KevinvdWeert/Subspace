-- Subspace Database Population Script
-- This script populates all tables with realistic fake data

USE Subspace;

-- Password hashes are SHA-256 of "password123" for all users (for demo purposes only)
INSERT INTO users (username, email, password_hash, role, created_at, updated_at) VALUES
('alexj', 'alex.johnson@example.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-01-15 10:30:00', '2023-06-20 14:25:00'),
('mariah_lee', 'mariah.lee@example.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-02-10 09:15:00', '2023-08-15 11:40:00'),
('coding_wizard', 'sam.chen@tech.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-03-05 16:45:00', '2023-09-10 08:20:00'),
('travel_bug', 'emily.travels@nomad.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-01-28 12:20:00', '2023-07-22 19:30:00'),
('music_lover', 'david.music@beat.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-04-12 14:10:00', '2023-10-05 16:45:00'),
('bookworm', 'sarah.reads@library.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-02-25 11:05:00', '2023-08-30 10:15:00'),
('gamer_4life', 'mike.games@play.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-03-18 20:30:00', '2023-09-25 22:10:00'),
('fitness_guru', 'lisa.fit@health.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-01-10 07:45:00', '2023-07-15 18:20:00'),
('foodie_expert', 'jamie.food@chef.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-04-01 13:25:00', '2023-10-18 12:35:00'),
('art_soul', 'chloe.art@canvas.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-02-15 15:50:00', '2023-08-10 09:45:00'),
('admin_jane', 'jane.admin@subspace.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'admin', '2023-01-01 08:00:00', '2023-11-01 10:00:00'),
('admin_mark', 'mark.sys@subspace.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'admin', '2023-01-01 09:00:00', '2023-11-05 14:30:00'),
('tech_guru', 'ryan.tech@geek.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-03-22 11:40:00', '2023-09-28 17:20:00'),
('nature_lover', 'olivia.nature@green.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-02-05 10:15:00', '2023-08-12 13:25:00'),
('movie_buff', 'tom.cinema@film.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'user', '2023-04-08 19:20:00', '2023-10-22 20:45:00');

-- Insert user profiles
INSERT INTO profiles (user_id, display_name, bio, avatar_url, updated_at) VALUES
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
(15, 'Tom Screen', 'Film critic and movie buff. Watched 500+ films this year! 🎬', 'https://api.dicebear.com/7.x/avataaars/svg?seed=Tom', '2023-10-22 20:45:00');

-- Insert posts
INSERT INTO posts (user_id, content, link_url, media_url, is_hidden, created_at, updated_at) VALUES
(1, 'Just deployed my first machine learning model to production! It''s been a challenging but rewarding journey. #AI #ML #Tech', NULL, 'https://images.unsplash.com/photo-1555949963-aa79dcee981c', 0, '2023-11-01 09:15:00', NULL),
(2, 'Sunset in Bali never gets old. The colors today were absolutely breathtaking! 🌅', NULL, 'https://images.unsplash.com/photo-1537996194471-e657df975ab4', 0, '2023-11-02 14:30:00', NULL),
(3, 'Anyone else excited about the new JavaScript framework announcements? The ecosystem moves so fast! #webdev #javascript', 'https://dev.to/news', NULL, 0, '2023-11-03 10:45:00', NULL),
(4, 'Just arrived in Tokyo! The food here is incredible. First stop: ramen! 🍜', NULL, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624', 0, '2023-11-04 18:20:00', NULL),
(5, 'Working on a new album. This one''s going to be different - experimenting with electronic folk fusion. Thoughts? #musicproduction', NULL, NULL, 0, '2023-11-05 20:10:00', NULL),
(6, 'Just finished "Project Hail Mary" by Andy Weir. What an incredible book! The science, the characters, the ending... perfection. 📚', NULL, NULL, 0, '2023-11-06 11:30:00', NULL),
(7, 'New high score in the tournament today! The competition was fierce but we pulled through. #esports #gaming', 'https://twitch.tv', NULL, 0, '2023-11-07 22:45:00', NULL),
(8, 'Try this 15-minute morning workout routine: 1. Jumping jacks 2. Push-ups 3. Squats 4. Plank Repeat 3x! #fitness #workout', NULL, NULL, 0, '2023-11-08 07:15:00', NULL),
(9, 'Experimented with Korean-Mexican fusion tonight: Bulgogi tacos with kimchi slaw. Surprisingly delicious! #foodie #recipes', NULL, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b', 0, '2023-11-09 19:30:00', NULL),
(10, 'New digital painting complete! This one was inspired by Japanese folklore. Swipe to see the process. #art #digitalart', NULL, 'https://images.unsplash.com/photo-1541961017774-22349e4a1262', 0, '2023-11-10 16:40:00', NULL),
(1, 'Looking for recommendations on good MLOps tools. Currently exploring Kubeflow vs MLflow. What''s your experience? #MLOps', NULL, NULL, 0, '2023-11-11 13:20:00', NULL),
(13, 'Just got my hands on the new smartphone. The camera improvements are revolutionary! Full review coming soon. #techreview', NULL, NULL, 0, '2023-11-12 15:55:00', NULL),
(14, 'Planted 50 trees today with the local community. Every little bit helps! #environment #sustainability', NULL, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef', 0, '2023-11-13 12:10:00', NULL),
(15, 'Just watched the latest sci-fi film. Without spoilers: the visual effects were stunning but the plot needed work. #moviereview', NULL, NULL, 0, '2023-11-14 21:05:00', NULL),
(2, 'Remote work from a beach cafe in Thailand. This is the life! #digitalnomad #remotework', NULL, 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963', 0, '2023-11-15 08:30:00', NULL);

-- Insert post likes (users liking various posts)
INSERT INTO post_likes (post_id, user_id, created_at) VALUES
(1, 2, '2023-11-01 10:30:00'),
(1, 3, '2023-11-01 11:45:00'),
(1, 5, '2023-11-01 14:20:00'),
(2, 1, '2023-11-02 16:10:00'),
(2, 4, '2023-11-02 17:25:00'),
(2, 6, '2023-11-02 18:40:00'),
(2, 8, '2023-11-02 19:15:00'),
(3, 1, '2023-11-03 11:30:00'),
(3, 7, '2023-11-03 12:45:00'),
(3, 9, '2023-11-03 13:20:00'),
(4, 2, '2023-11-04 19:40:00'),
(4, 5, '2023-11-04 20:15:00'),
(4, 10, '2023-11-04 21:30:00'),
(5, 6, '2023-11-05 21:45:00'),
(5, 8, '2023-11-05 22:10:00'),
(6, 1, '2023-11-06 12:45:00'),
(6, 9, '2023-11-06 13:20:00'),
(6, 14, '2023-11-06 14:35:00'),
(7, 3, '2023-11-07 23:10:00'),
(7, 5, '2023-11-08 00:25:00'),
(8, 4, '2023-11-08 08:30:00'),
(8, 7, '2023-11-08 09:15:00'),
(8, 9, '2023-11-08 10:40:00'),
(9, 1, '2023-11-09 20:45:00'),
(9, 2, '2023-11-09 21:20:00'),
(9, 5, '2023-11-09 22:10:00'),
(10, 3, '2023-11-10 17:30:00'),
(10, 6, '2023-11-10 18:45:00'),
(10, 9, '2023-11-10 19:20:00'),
(11, 3, '2023-11-11 14:40:00'),
(11, 13, '2023-11-11 15:25:00'),
(12, 1, '2023-11-12 16:50:00'),
(12, 4, '2023-11-12 17:35:00'),
(13, 2, '2023-11-13 13:45:00'),
(13, 10, '2023-11-13 14:20:00'),
(13, 15, '2023-11-13 15:10:00'),
(14, 6, '2023-11-14 22:20:00'),
(14, 8, '2023-11-14 23:05:00'),
(15, 1, '2023-11-15 09:45:00'),
(15, 3, '2023-11-15 10:20:00'),
(15, 4, '2023-11-15 11:35:00');

-- Insert post comments
INSERT INTO post_comments (post_id, user_id, content, is_hidden, created_at) VALUES
(1, 3, 'Congratulations Alex! Which framework did you use? TensorFlow or PyTorch?', 0, '2023-11-01 11:20:00'),
(1, 1, '@coding_wizard Thanks! I went with PyTorch - really loving their dynamic computation graphs.', 0, '2023-11-01 11:45:00'),
(2, 4, 'Bali is magical! Have you been to Ubud yet? The rice terraces are incredible.', 0, '2023-11-02 16:45:00'),
(2, 2, '@travel_bug Not yet! That''s next on my list for tomorrow. Any specific recommendations?', 0, '2023-11-02 17:10:00'),
(3, 1, 'The JS ecosystem exhaustion is real! Sometimes I miss the jQuery days 😅', 0, '2023-11-03 11:50:00'),
(4, 5, 'Try the tsukemen at Rokurinsha in Tokyo Station! Best I''ve ever had.', 0, '2023-11-04 20:30:00'),
(5, 6, 'Electronic folk fusion sounds fascinating! Any artists that inspired this direction?', 0, '2023-11-05 21:25:00'),
(6, 10, 'That book is on my list! How does it compare to The Martian?', 0, '2023-11-06 13:40:00'),
(6, 6, '@art_soul I actually think it''s better! The character development is more nuanced.', 0, '2023-11-06 14:15:00'),
(7, 9, 'What game was this for? Missed the stream unfortunately.', 0, '2023-11-07 23:45:00'),
(7, 7, '@foodie_expert It was the Valorant Champions Tour qualifiers! VOD is on my channel.', 0, '2023-11-08 00:20:00'),
(8, 2, 'Thanks for sharing! Going to try this routine tomorrow morning.', 0, '2023-11-08 08:45:00'),
(9, 1, 'That sounds amazing! Would you share the recipe?', 0, '2023-11-09 21:10:00'),
(10, 8, 'Stunning work as always Chloe! The color palette is beautiful.', 0, '2023-11-10 18:20:00'),
(11, 3, 'We''re using MLflow at my company and it''s been great for experiment tracking.', 0, '2023-11-11 15:10:00'),
(13, 1, 'Amazing initiative! How can others get involved with similar projects?', 0, '2023-11-13 13:30:00'),
(14, 15, 'I felt the same way! The third act really fell apart for me.', 0, '2023-11-14 22:45:00'),
(15, 1, 'Living the dream! How''s the internet speed there for remote work?', 0, '2023-11-15 10:10:00');

-- Insert user blocks (admin actions)
INSERT INTO user_blocks (user_id, blocked_by_admin_id, reason, blocked_until, created_at, revoked_at) VALUES
(7, 11, 'Spamming promotional content in comments', '2023-12-01 00:00:00', '2023-10-15 14:30:00', NULL),
(13, 12, 'Multiple inappropriate posts violating community guidelines', '2024-01-01 00:00:00', '2023-10-20 09:15:00', NULL),
(5, 11, 'Temporary suspension for heated arguments', '2023-11-01 00:00:00', '2023-10-10 16:45:00', '2023-10-25 11:20:00'); -- This one was revoked early

-- Display counts of inserted data
SELECT 
    'Database populated successfully!' AS message,
    (SELECT COUNT(*) FROM users) AS total_users,
    (SELECT COUNT(*) FROM profiles) AS total_profiles,
    (SELECT COUNT(*) FROM posts) AS total_posts,
    (SELECT COUNT(*) FROM post_likes) AS total_likes,
    (SELECT COUNT(*) FROM post_comments) AS total_comments,
    (SELECT COUNT(*) FROM user_blocks) AS total_blocks;