-- SQL Database Export for Wayamba Province Department of Social Services Web Portal
-- Database: social_services_nwp_db

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create Database
CREATE DATABASE IF NOT EXISTS `social_services_nwp_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `social_services_nwp_db`;

-- --------------------------------------------------------

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100),
  `role` ENUM('admin', 'user', 'staff') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `users`
INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`) VALUES
(1, 'admin', 'admin123', 'System Administrator', 'admin'),
(2, 'officer', 'password123', 'Welfare Officer', 'staff');

-- --------------------------------------------------------

-- Table structure for table `officers`
CREATE TABLE IF NOT EXISTS `officers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `title` VARCHAR(100),
  `phone` VARCHAR(20),
  `category` ENUM('executive', 'admin', 'technical', 'div', 'hq') NOT NULL,
  `division` VARCHAR(50),
  `email` VARCHAR(100) DEFAULT NULL,
  `photo_url` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `officers`
INSERT INTO `officers` (`id`, `name`, `title`, `phone`, `category`, `division`, `email`, `sort_order`) VALUES
(1, 'Deepthi Pradeepa De Silva', 'Administrative Officer', '037-2223483', 'hq', 'Head Office', 'deepthi.p@socialdept.nw.gov.lk', 2),
(2, 'G.G. Dilani Gunasinghe', 'Provincial Director - Social Services NWP', '037-2223483', 'executive', 'Head Office', 'dilani.g@socialdept.nw.gov.lk', 1),
(3, 'R. M. Pathirana', 'Assistant Director', '037-2223483', 'executive', 'Head Office', 'pathirana.r@socialdept.nw.gov.lk', 3),
(4, 'H. K. N. Herath', 'Chief Accountant', '037-2223483', 'admin', 'Head Office', 'acc.nwp@socialdept.nw.gov.lk', 4);

-- --------------------------------------------------------

-- Table structure for table `downloads`
CREATE TABLE IF NOT EXISTS `downloads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `category` VARCHAR(50),
  `file_url` VARCHAR(255) DEFAULT '#',
  `icon_class` VARCHAR(50) DEFAULT 'fa-file-alt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `downloads`
INSERT INTO `downloads` (`id`, `title`, `description`, `category`, `file_url`, `icon_class`) VALUES
(1, 'Public Assistance (Welfare) Form', 'Standard application form for requesting monthly financial aid.', 'forms', '#', 'fa-file-alt'),
(2, 'RTI Request Form - Sri Lanka', 'Official form to request information under the Right to Information Act.', 'circulars', '#', 'fa-info-circle'),
(3, 'Self-Employment Grant Application', 'Application form for livelihood grants up to LKR 50,000.', 'rates', '#', 'fa-hand-holding-hand'),
(4, 'Department Performance Report 2025', 'Annual performance report of the Department of Social Services NWP.', 'circulars', '#', 'fa-chart-bar');

-- --------------------------------------------------------

-- Table structure for table `site_sections`
CREATE TABLE IF NOT EXISTS `site_sections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `section_key` VARCHAR(50) UNIQUE NOT NULL,
  `content_en` TEXT,
  `content_si` TEXT,
  `content_ta` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `site_sections`
INSERT INTO `site_sections` (`section_key`, `content_en`, `content_si`, `content_ta`) VALUES
('about_overview', 'The Department of Social Services of the Wayamba Provincial Council is dedicated to enhancing the welfare and social development of vulnerable populations including elders, disabled individuals, and impoverished citizens in the North Western Province.', 'වයඹ පළාත් සභා සමාජ සේවා දෙපාර්තමේන්තුව වයඹ පළාතේ වැඩිහිටියන්, ආබාධිත පුද්ගලයන් සහ අසරණභාවයට පත් වූවන් ඇතුළු අවදානමට ලක්විය හැකි ජන කොටස්වල සුභසාධනය සහ සමාජ සංවර්ධනය නැංවීම සඳහා කැපවී සිටී.', 'வடமேல் மாகாண சபை சமூக சேவைகள் திணைக்களம் வடமேல் மாகாணத்தில் உள்ள முதியவர்கள், மாற்றுத்திறனாளி நபர்கள் மற்றும் ஏழைகள் உள்ளிட்ட பாதிக்கப்படக்கூடிய மக்களின் நலன் மற்றும் சமூக மேம்பாட்டை மேம்படுத்துவதற்காக அர்ப்பணிக்கப்பட்டுள்ளது.'),
('about_objectives', 'To provide equitable, accessible, and high-quality social welfare and relief services, empowering disadvantaged groups and integrating them into the mainstream of national development.', 'අවාසි සහගත කණ්ඩායම් සවිබල ගැන්වීම සහ ඔවුන් ජාතික සංවර්ධනයේ ප්‍රධාන ප්‍රවාහයට ඒකාබද්ධ කිරීම සඳහා සාධාරණ, ප්‍රවේශ විය හැකි සහ උසස් තත්ත්වයේ සමාජ සුභසාධන සහ සහන සේවා සැපයීම.', 'பாதிக்கப்பட்ட குழுக்களுக்கு அதிகாரம் அளித்தல் மற்றும் அவர்களை தேசிய வளர்ச்சியின் முக்கிய நீரோட்டத்தில் ஒருங்கிணைத்தல், சமத்துவமான மற்றும் உயர்தர சமூக நலன் மற்றும் நிவாரண சேவைகளை வழங்குதல்.'),
('about_achievements', 'Empowered thousands of individuals with self-employment grants, established specialized care facilities, and actively integrated vulnerable families into community welfare projects.', 'ස්වයං රැකියා ආධාර මගින් දහස් ගණනක් පුද්ගලයින් සවිබල ගැන්වීම, විශේෂිත සත්කාර පහසුකම් ස්ථාපිත කිරීම සහ අවදානමට ලක්විය හැකි පවුල් ප්‍රජා සුභසාධන ව්‍යාපෘතිවලට ක්‍රියාකාරීව ඒකාබද්ධ කිරීම.', 'சுயதொழில் மானியங்கள் மூலம் ஆயிரக்கணக்கான தனிநபர்களுக்கு அதிகாரம் அளித்தல், சிறப்பு பராமரிப்பு வசதிகளை நிறுவுதல் மற்றும் பாதிக்கப்படக்கூடிய குடும்பங்களை சமூக நலத் திட்டங்களில் தீவிரமாக ஒருங்கிணைத்தல்.'),
('contact_address', 'Provincial Council Complex, Kurunegala', 'පළාත් සභා සංකීර්ණය, කුරුණෑගල', 'மாகாண சபை வளாகம், குருநாகல்'),
('contact_phone', '037-2223483', '037-2223483', '037-2223483'),
('contact_email', 'socidepnwp@gmail.com', 'socidepnwp@gmail.com', 'socidepnwp@gmail.com'),
('contact_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126490.13327670732!2d80.28841443690623!3d7.494747385966427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae33a1e944b419b%3A0xe542385cc820b924!2sKurunegala!5e0!3m2!1sen!2slk!4v1714207907572!5m2!1sen!2slk', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126490.13327670732!2d80.28841443690623!3d7.494747385966427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae33a1e944b419b%3A0xe542385cc820b924!2sKurunegala!5e0!3m2!1sen!2slk!4v1714207907572!5m2!1sen!2slk', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126490.13327670732!2d80.28841443690623!3d7.494747385966427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae33a1e944b419b%3A0xe542385cc820b924!2sKurunegala!5e0!3m2!1sen!2slk!4v1714207907572!5m2!1sen!2slk'),
('news_bar', 'Welcome to the Official Web Portal of the Wayamba Province Social Services Department - Serving the poor, elderly, and disabled individuals with compassion.', 'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුවේ නිල වෙබ් අඩවිය වෙත සාදරයෙන් පිළිගනිමු - අසරණ, වැඩිහිටි සහ ආබාධිත පුද්ගලයන්ගේ සුභසාධනය උදෙසා කැපවෙමු.', 'வடமேல் மாகாண சமூக சேவைகள் திணைக்களத்தின் அதிகாரப்பூர்வ இணையத்தளத்திற்கு உங்களை வரவேற்கிறோம்.'),
('service_inv_list', 'Elderly Care and Institutional Support\nDisability Welfare and Assistance Programs\nSelf-Employment and Livelihood Support\nVocational Training and Rehabilitation\nDisaster and Financial Relief Schemes', 'වැඩිහිටි සත්කාර සහ ආයතනික සහාය\nආබාධිත සුභසාධන සහ ආධාර වැඩසටහන්\nස්වයං රැකියා සහ ජීවනෝපාය ආධාර\nවෘත්තීය පුහුණුව සහ පුනරුත්ථාපනය\nආපදා සහ මූල්‍ය සහන යෝජනා ක්‍රම', 'முதியோர் பராமரிப்பு மற்றும் நிறுவன ஆதரவு\nமாற்றுத்திறனாளிகள் நலன் மற்றும் உதவித் திட்டங்கள்\nசுயதொழில் மற்றும் வாழ்வாதார ஆதரவு\nதொழில் பயிற்சி மற்றும் மறுவாழ்வு\nபேரிடர் மற்றும் நிதி நிவாரணத் திட்டங்கள்'),
('service_eng_list', 'Registration of vocational trainees\nCurriculum planning for social work education\nConducting annual certificate courses\nProviding starter kits for self-employment', 'වෘත්තීය පුහුණුලාභීන් ලියාපදිංචි කිරීම\nසමාජ වැඩ අධ්‍යාපනය සඳහා විෂයමාලා සැලසුම් කිරීම\nවාර්ෂික සහතික පත්‍ර පාඨමාලා පැවැත්වීම\nස්වයං රැකියා සඳහා ආරම්භක කට්ටල ලබාදීම', 'தொழில் பயிற்சி பெறுபவர்களின் பதிவு\nசமூகப் பணி கல்விக்கான பாடத்திட்ட திட்டமிடல்\nஆண்டு சான்றிதழ் படிப்புகளை நடத்துதல்\nசுயதொழிலுக்கான தொடக்கக் கருவிகளை வழங்குதல்'),
('service_eng_desc', 'Our department has established protocols to verify, process, and disburse emergency financial aid and vocational equipment support directly to eligible beneficiaries across all divisions in the province.', 'අපගේ දෙපාර්තමේන්තුව පළාතේ සියලුම අංශවල සුදුසුකම් ලත් ප්‍රතිලාභීන් වෙත හදිසි මූල්‍ය ආධාර සහ වෘත්තීය උපකරණ ආධාර සෘජුවම ලබා දීම සඳහා ක්‍රියා පටිපාටි සකස් කර ඇත.', 'மாகாணத்தில் உள்ள அனைத்து பிரிவுகளிலும் தகுதியுள்ள பயனாளிகளுக்கு அவசர நிதி உதவி மற்றும் தொழில் உபகரண உதவிகளை நேரடியாக வழங்குவதற்கான நெறிமுறைகளை எங்கள் துறை நிறுவியுள்ளது.'),
('service_const_list', 'Supervising registered elders homes\nImplementing standard safety guidelines\nProvincial council funding for infrastructure upgrades\nAnnual health checkups and recreational programs', 'ලියාපදිංචි වැඩිහිටි නිවාස අධීක්ෂණය\nසම්මත ආරක්ෂණ මාර්ගෝපදේශ ක්‍රියාත්මක කිරීම\nයටිතල පහසුකම් වැඩිදියුණු කිරීම සඳහා පළාත් සභා ප්‍රතිපාදන\nවාර්ෂික සෞඛ්‍ය පරීක්ෂණ සහ විනෝදාත්මක වැඩසටහන්', 'பதிவு செய்யப்பட்ட முதியோர் இல்லங்களை கண்காணித்தல்\nநிலையான பாதுகாப்பு வழிகாட்டுதல்களை செயல்படுத்துதல்\nஉள்கட்டமைப்பு மேம்பாடுகளுக்கான மாகாண சபை நிதி\nஆண்டு சுகாதார பரிசோதனைகள் மற்றும் பொழுதுபோக்கு திட்டங்கள்'),
('service_const_desc', 'We monitor and support the operation of both state-run and non-governmental elders homes to ensure high standards of living, dignity, and care for our senior citizens.', 'අපගේ ජ්‍යෙෂ්ඨ පුරවැසියන් සඳහා උසස් ජීවන තත්ත්වයක්, ගෞරවයක් සහ රැකවරණයක් සහතික කිරීම සඳහා රජයේ මෙන්ම රාජ්‍ය නොවන වැඩිහිටි නිවාසවල ක්‍රියාකාරිත්වය අපි නිරීක්ෂණය කර සහාය වෙමු.', 'எங்கள் மூத்த குடிமக்களுக்கு உயர்தர வாழ்க்கை, கண்ணியம் மற்றும் பராமரிப்பை உறுதி செய்வதற்காக அரசு நடத்தும் மற்றும் அரசு சாரா முதியோர் இல்லங்களின் செயல்பாடுகளை நாங்கள் கண்காணித்து ஆதரவளிக்கிறோம்.'),
('service_op_list', 'Rehabilitation and counseling sessions for drug abuse victims\nCommunity re-integration support', 'මත්ද්‍රව්‍යවලට ගොදුරු වූවන් සඳහා පුනරුත්ථාපන සහ උපදේශන සැසි\nප්‍රජා ඒකාබද්ධතා සහාය', 'போதைப்பொருளால் பாதிக்கப்பட்டவர்களுக்கான மறுவாழ்வு மற்றும் ஆலோசனை அமர்வுகள்\nசமூக மறு ஒருங்கிணைப்பு ஆதரவு'),
('service_inst_list', 'Annual training workshops for social workers\nCounseling skill development courses', 'සමාජ සේවකයින් සඳහා වාර්ෂික පුහුණු වැඩමුළු\nඋපදේශන නිපුණතා සංවර්ධන පාඨමාලා', 'சமூகப் பணியாளர்களுக்கான ஆண்டு பயிற்சிப் பட்டறைகள்\nஆலோசனை திறன் மேம்பாட்டு படிப்புகள்'),
('header_national_logo', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png'),
('header_provincial_logo', 'Nwp_sri_lanka.png', 'Nwp_sri_lanka.png', 'Nwp_sri_lanka.png'),
('header_title_en', 'DEPARTMENT OF SOCIAL SERVICES - NWP', 'DEPARTMENT OF SOCIAL SERVICES - NWP', 'DEPARTMENT OF SOCIAL SERVICES - NWP'),
('header_title_si', 'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව', 'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව', 'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව'),
('header_title_ta', 'வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்', 'வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்', 'வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்');

-- --------------------------------------------------------

-- Table structure for table `procurements`
CREATE TABLE IF NOT EXISTS `procurements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `publish_date` DATE NOT NULL,
  `file_url` VARCHAR(255) DEFAULT '#',
  `status` ENUM('active', 'expired') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `procurements`
INSERT INTO `procurements` (`id`, `title`, `publish_date`, `file_url`, `status`) VALUES
(1, 'Supply of Food Items for Registered Elders Homes 2026', '2026-05-20', '#', 'active'),
(2, 'Supply of Wheelchairs & Assistive Devices (Tender Social/NWP/04)', '2026-05-18', '#', 'active'),
(3, 'Refurbishment of Divisional Social Services Offices', '2026-04-15', '#', 'expired');

-- --------------------------------------------------------

-- Table structure for table `projects`
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('summary', 'key', 'completed') NOT NULL,
  `title_en` VARCHAR(255) NOT NULL,
  `title_si` VARCHAR(255),
  `title_ta` VARCHAR(255),
  `description_en` TEXT,
  `description_si` TEXT,
  `description_ta` TEXT,
  `image_url` TEXT DEFAULT NULL,
  `financial_details` VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `projects`
INSERT INTO `projects` (`id`, `category`, `title_en`, `title_si`, `title_ta`, `description_en`, `description_si`, `description_ta`, `image_url`, `financial_details`) VALUES
(1, 'summary', 'Welfare Beneficiaries Summary', 'සුභසාධන ප්‍රතිලාභීන්ගේ සාරාංශය', 'நலன்புரி பயனாளிகளின் சுருக்கம்', 'Total individuals supported under monthly assistance schemes in 2026.', '2026 වර්ෂයේ මාසික ආධාර යෝජනා ක්‍රම යටතේ උපකාර ලැබූ මුළු පුද්ගලයින් සංඛ්‍යාව.', '2026 ஆம் ஆண்டில் மாதாந்திர உதவித் திட்டங்களின் கீழ் ஆதரிக்கப்படும் மொத்த நபர்கள்.', NULL, '18,500 Registered Beneficiaries'),
(2, 'key', 'Establishment of Elderly Care & Activity Centers', 'වැඩිහිටි සත්කාර සහ ක්‍රියාකාරකම් මධ්‍යස්ථාන පිහිටුවීම', 'முதியோர் பராமரிப்பு மற்றும் செயல்பாட்டு மையங்கள் நிறுவுதல்', 'Inauguration of new elders welfare and support desk offices in Kurunegala divisions.', 'කුරුණෑගල කොට්ඨාශවල නව වැඩිහිටි සුභසාධන සහ උපකාරක කවුළු කාර්යාල විවෘත කිරීම.', 'குருநாகல் பிரிவுகளில் புதிய முதியோர் நலன் மற்றும் ஆதரவு மையங்கள் திறப்பு.', 'slider3.jpg', 'Est. LKR 80 Million'),
(3, 'completed', 'Senior Citizen Vocational Center Phase 1', 'ජ්‍යෙෂ්ඨ පුරවැසි වෘත්තීය මධ්‍යස්ථානය පියවර 1', 'மூத்த குடிமக்கள் தொழிற்பயிற்சி மையம் கட்டம் 1', 'Completion of structural refurbishments for the provincial elders vocational and activity center.', 'පළාත් වැඩිහිටි වෘත්තීය සහ ක්‍රියාකාරකම් මධ්‍යස්ථානයේ ව්‍යුහාත්මක ප්‍රතිසංස්කරණ කටයුතු නිම කිරීම.', 'மாகாண முதியோர் தொழில் மற்றும் செயல்பாட்டு மையத்திற்கான உள்கட்டமைப்பு சீரமைப்பு பணிகள் நிறைவடைந்தது.', 'logo2.jpg', 'Completed - LKR 24M');

-- --------------------------------------------------------

-- Table structure for table `gallery`
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `gallery`
INSERT INTO `gallery` (`id`, `title`, `image_url`, `description`) VALUES
(1, 'Wheelchair Donation Drive', 'slider1.jpg', 'Donation of mobility aids to registered disabled individuals at the Kurunegala Secretariat.'),
(2, 'Vocational Training Exhibition', 'slider4.jpg', 'Exhibition of handcraft items prepared by trainees from the provincial rehabilitation centers.'),
(3, 'Senior Citizens Day Program', 'slider5.jpg', 'Cultural activities and health checkups organized for the elders at the provincial care home.');

-- --------------------------------------------------------

-- Table structure for table `announcements`
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('internal', 'outside') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) DEFAULT '#',
  `badge` VARCHAR(20) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `announcements`
INSERT INTO `announcements` (`id`, `category`, `title`, `url`, `badge`) VALUES
(1, 'internal', 'Disaster Relief Funding Released for NWP', '#', 'New'),
(2, 'internal', 'Applications open for self-employment grants', '#', NULL),
(3, 'internal', 'Annual Elders Day Art Exhibition entries open', '#', NULL),
(4, 'internal', 'Special staff vacancy: Counselor (Contract basis)', '#', NULL),
(5, 'internal', 'Elders Home Caretaker Workshop', '#', NULL),
(6, 'outside', 'National Social Welfare Policy draft for public review', '#', 'New'),
(7, 'outside', 'WHO Rehabilitation and Assistive Technology workshop', '#', NULL);

-- --------------------------------------------------------

-- Table structure for table `news`
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('dept-news', 'prov-news') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `news_date` DATE NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `image_before` TEXT DEFAULT NULL,
  `image_after` TEXT DEFAULT NULL,
  `url` VARCHAR(255) DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `news`
INSERT INTO `news` (`id`, `category`, `title`, `news_date`, `content`, `image_url`) VALUES
(1, 'dept-news', 'Establishment of Elderly Care & Activity Centers', '2026-05-12', 'The Social Services Department opened three elders welfare support centers in Kurunegala to assist seniors in the province.', 'slider3.jpg'),
(2, 'dept-news', 'Distribution of Assistive Devices', '2026-05-02', 'Successfully distributed 150 wheelchairs and hearing aids to low-income disabled citizens in Wayamba.', 'logo2.jpg'),
(3, 'prov-news', 'Wayamba Provincial Council Welfare Allocations', '2026-05-25', 'The provincial council approved LKR 300 Million for welfare programs targeting seniors and disabled individuals.', 'slider1.jpg');

-- --------------------------------------------------------

-- Table structure for table `courses_events`
CREATE TABLE IF NOT EXISTS `courses_events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('upcoming', 'completed') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `event_date` DATE NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `icon_class` VARCHAR(50) DEFAULT 'fa-graduation-cap',
  `url` VARCHAR(255) DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `courses_events`
INSERT INTO `courses_events` (`id`, `category`, `title`, `event_date`, `location`, `icon_class`) VALUES
(1, 'upcoming', 'Sign Language and Communication Training', '2026-06-15', 'Kurunegala Secretariat Hall', 'fa-universal-access'),
(2, 'upcoming', 'Elderly Counseling & Mental Health Seminar', '2026-07-01', 'Head Office auditorium', 'fa-universal-access'),
(3, 'completed', 'Social Caretaker Training Program', '2026-03-10', 'Wayamba Training Center', 'fa-heart-circle-check');

-- --------------------------------------------------------

-- Table structure for table `important_links`
CREATE TABLE IF NOT EXISTS `important_links` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('govt-links', 'tech-links') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `important_links`
INSERT INTO `important_links` (`id`, `category`, `title`, `url`, `image_url`) VALUES
(1, 'govt-links', 'Wayamba Provincial Council Portal', 'http://www.nw.gov.lk', '1200px-Flag_of_the_North_Western_Province_(Sri_Lanka).svg.png'),
(2, 'govt-links', 'Ministry of Social Empowerment - Sri Lanka', 'http://www.socialemw.gov.lk', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/100px-Emblem_of_Sri_Lanka.svg.png'),
(3, 'govt-links', 'RTI Commission of Sri Lanka', 'http://www.rticommission.lk', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/100px-Emblem_of_Sri_Lanka.svg.png'),
(4, 'tech-links', 'National Institute of Social Development (NISD)', 'http://www.nisd.ac.lk', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/100px-Emblem_of_Sri_Lanka.svg.png');

-- --------------------------------------------------------

-- Table structure for table `suggestions`
CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `subject` VARCHAR(150),
  `message` TEXT NOT NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `banners`
CREATE TABLE IF NOT EXISTS `banners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) DEFAULT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `banners`
INSERT INTO `banners` (`id`, `title`, `image_url`, `sort_order`) VALUES
(1, 'Empowering Vulnerable Communities', 'slider1.jpg', 1),
(2, 'Caring for Our Elders', 'slider2.jpg', 2),
(3, 'Social Development & Integration', 'slider3.jpg', 3),
(4, 'Vocational Training Centers', 'slider4.jpg', 4),
(5, 'Caring Communities for Seniors', 'slider5.jpg', 5);

-- --------------------------------------------------------

-- Table structure for table `hall_bookings`
CREATE TABLE IF NOT EXISTS `hall_bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_date` DATE UNIQUE NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `booked_by` VARCHAR(100) NOT NULL,
  `status` ENUM('approved', 'pending') DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `hall_bookings`
INSERT INTO `hall_bookings` (`id`, `booking_date`, `title`, `booked_by`) VALUES
(1, '2026-06-10', 'Monthly Progress Review Meeting', 'Director\'s Office'),
(2, '2026-06-15', 'Staff Sign Language Training', 'Welfare Division'),
(3, '2026-06-22', 'Elders Home Caretakers Workshop', 'Elderly Care Division');

-- --------------------------------------------------------

-- Table structure for table `services`
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title_en` VARCHAR(255) NOT NULL,
  `title_si` VARCHAR(255) NOT NULL,
  `title_ta` VARCHAR(255) NOT NULL,
  `short_desc_en` VARCHAR(255) NOT NULL,
  `short_desc_si` VARCHAR(255) NOT NULL,
  `short_desc_ta` VARCHAR(255) NOT NULL,
  `icon_class` VARCHAR(50) DEFAULT 'fa-concierge-bell',
  `icon_bg` VARCHAR(255) DEFAULT 'linear-gradient(135deg, #1e3a5f, #2563eb)',
  `bullets_en` TEXT DEFAULT NULL,
  `bullets_si` TEXT DEFAULT NULL,
  `bullets_ta` TEXT DEFAULT NULL,
  `long_desc_en` TEXT DEFAULT NULL,
  `long_desc_si` TEXT DEFAULT NULL,
  `long_desc_ta` TEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding data for table `services`
INSERT INTO `services` (`id`, `title_en`, `title_si`, `title_ta`, `short_desc_en`, `short_desc_si`, `short_desc_ta`, `icon_class`, `icon_bg`, `bullets_en`, `bullets_si`, `bullets_ta`, `long_desc_en`, `long_desc_si`, `long_desc_ta`, `sort_order`) VALUES
(1, 'Public Assistance', 'මහජනාධාර ලබාදීම', 'பொது உதவி', 'Provision of public assistance for low-income communities', 'අඩු ආදායම්ලාභී ප්‍රජාව සඳහා මහජනාධාර ලබාදීම', 'குறைந்த வருமானம் பெறும் சமூகங்களுக்கான பொது உதவி வழங்குதல்', 'fa-hand-holding-usd', 'linear-gradient(135deg, #1e3a5f, #2563eb)', 'Elderly Care and Institutional Support\nDisability Welfare and Assistance Programs\nSelf-Employment and Livelihood Support\nVocational Training and Rehabilitation\nDisaster and Financial Relief Schemes', 'වැඩිහිටි සත්කාර සහ ආයතනික සහාය\nආබාධිත සුභසාධන සහ ආධාර වැඩසටහන්\nස්වයං රැකියා සහ ජීවනෝපාය ආධාර\nවෘත්තීය පුහුණුව සහ පුනරුත්ථාපනය\nආපදා සහ මූල්‍ය සහන යෝජනා ක්‍රම', 'முதியோர் பராமரிப்பு மற்றும் நிறுவன ஆதரவு\nமாற்றுத்திறனாளிகள் நலன் மற்றும் உதவித் திட்டங்கள்\nசுயதொழில் மற்றும் வாழ்வாதார ஆதரவு\nதொழில் பயிற்சி மற்றும் மறுவாழ்வு\nபேரிடர் மற்றும் நிதி நிவாரணத் திட்டங்கள்', '', '', '', 1),
(2, 'Sisumina Scholarship', 'සිසුමිණ ශිෂ්‍යාධාර', 'சிசுமின கல்வி உதவித்தொகை', 'Educational assistance for children from vulnerable, widowed, or disabled families', 'වැන්දඹු, විසුරුණු, රෝගී ආබාධ සහිත හා අසරණ පවුල් වල දරුවන් සඳහා ශිෂ්‍යාධාර', 'கணவனை இழந்த, நோய்வாய்ப்பட்ட மற்றும் ஏழை குடும்பங்களைச் சேர்ந்த குழந்தைகளின் கல்விக்கான உதவி', 'fa-graduation-cap', 'linear-gradient(135deg, #6d28d9, #8b5cf6)', 'Registration of vocational trainees\nCurriculum planning for social work education\nConducting annual certificate courses\nProviding starter kits for self-employment', 'වෘත්තීය පුහුණුලාභීන් ලියාපදිංචි කිරීම\nසමාජ වැඩ අධ්‍යාපනය සඳහා විෂයමාලා සැලසුම් කිරීම\nවාර්ෂික සහතික පත්‍ර පාඨමාලා පැවැත්වීම\nස්වයං රැකියා සඳහා ආරම්භක කට්ටල ලබාදීම', 'தொழில் பயிற்சி பெறுபவர்களின் பதிவு\nசமூகப் பணி கல்விக்கான பாடத்திட்ட திட்டமிடல்\nஆண்டு சான்றிதழ் படிப்புகளை நடத்துதல்\nசுயதொழிலுக்கான தொடக்கக் கருவிகளை வழங்குதல்', 'Our department has established protocols to verify, process, and disburse emergency financial aid and vocational equipment support directly to eligible beneficiaries across all divisions in the province.', 'අපගේ දෙපාර්තමේන්තුව පළාතේ සියලුම අංශවල සුදුසුකම් ලත් ප්‍රතිලාභීන් වෙත හදිසි මූල්‍ය ආධාර සහ වෘත්තීය උපකරණ ආධාර සෘජුවම ලබා දීම සඳහා ක්‍රියා පටිපාටි සකස් කර ඇත.', 'மாகாணத்தில் உள்ள அனைத்து பிரிவுகளிலும் தகுதியுள்ள பயனாளிகளுக்கு அவசர நிதி உதவி மற்றும் தொழில் உபகரண உதவிகளை நேரடியாக வழங்குவதற்கான நெறிமுறைகளை எங்கள் துறை நிறுவியுள்ளது.', 2),
(3, 'Housing Assistance', 'නිවාස ආධාර', 'வீட்டு வசதி உதவி', 'Providing housing construction and repair grants to low-income families', 'අඩු ආදායම්ලාභී පවුල් සඳහා පිළිසරණීය නිවාස ආධාර ලබාදීම', 'குறைந்த வருமானம் கொண்ட குடும்பங்களுக்கு வீட்டு வசதி உதவிகளை வழங்குதல்', 'fa-home', 'linear-gradient(135deg, #065f46, #10b981)', 'Supervising registered elders homes\nImplementing standard safety guidelines\nProvincial council funding for infrastructure upgrades\nAnnual health checkups and recreational programs', 'ලියාපදිංචි වැඩිහිටි නිවාස අධීක්ෂණය\nසම්මත ආරක්ෂණ මාර්ගෝපදේශ ක්‍රියාත්මක කිරීම\nයටිතල පහසුකම් වැඩිදියුණු කිරීම සඳහා පළාත් සභා ප්‍රතිපාදන\nවාර්ෂික සෞඛ්‍ය පරීක්ෂණ සහ විනෝදාත්මක වැඩසටහන්', 'பதிவு செய்யப்பட்ட முதியோர் இல்லங்களை கண்காணித்தல்\nநிலையான பாதுகாப்பு வழிகாட்டுதல்களை செயல்படுத்துதல்\nஉள்கட்டமைப்பு மேம்பாடுகளுக்கான மாகாண சபை நிதி\nஆண்டு சுகாதார பரிசோதனைகள் மற்றும் பொழுதுபோக்கு திட்டங்கள்', 'We monitor and support the operation of both state-run and non-governmental elders homes to ensure high standards of living, dignity, and care for our senior citizens.', 'අපගේ ජ්‍යෙෂ්ඨ පුරවැසියන් සඳහා උසස් ජීවන තත්ත්වයක්, ගෞරවයක් සහ රැකවරණයක් සහතික කිරීම සඳහා රජයේ මෙන්ම රාජ්‍ය නොවන වැඩිහිටි නිවාසවල ක්‍රියාකාරිත්වය අපි නිරීක්ෂණය කර සහාය වෙමු.', 'எங்கள் மூத்த குடிமக்களுக்கு உயர்தர வாழ்க்கை, கண்ணியம் மற்றும் பராமரிப்பை உறுதி செய்வதற்காக அரசு நடத்தும் மற்றும் அரசு சாரா முதியோர் இல்லங்களின் செயல்பாடுகளை நாங்கள் கண்காணித்து ஆதரவளிக்கிறோம்.', 3),
(4, 'Leprosy Patient Assistance', 'ලාදුරු රෝගය ආධාර', 'தொழுநோய் நோயாளிக்கான உதவி', 'Monthly financial medical assistance for registered leprosy patients', 'ලාදුරු රෝගය වැළදුනු අඩු ආදායම්ලාභීන් සඳහා වෛද්‍ය නිර්දේශය මත ආධාර ගෙවීම', 'தொழுநோயால் பாதிக்கப்பட்ட குறைந்த வருமானம் உடையவர்களுக்கு மாதாந்திர உதவி', 'fa-medkit', 'linear-gradient(135deg, #9f1239, #e11d48)', 'Rehabilitation and counseling sessions for drug abuse victims\nCommunity re-integration support', 'මත්ද්‍රව්‍යවලට ගොදුරු වූවන් සඳහා පුනරුත්ථාපන සහ උපදේශන සැසි\nප්‍රජා ඒකාබද්ධතා සහාය', 'போதைப்பொருளால் பாதிக்கப்பட்டவர்களுக்கான மறுவாழ்வு மற்றும் ஆலோசனை அமர்வுகள்\nசமூக மறு ஒருங்கிணைப்பு ஆதரவு', '', '', '', 4),
(5, 'Special Medical Assistance', 'විශේෂ වෛද්‍යාධාර', 'சிறப்பு மருத்துவ உதவி', 'Assistance for patients requiring long-term medical treatments and drugs', 'දීර්ඝ කාලීනව ප්‍රතිකාර ලබාගත යුතු රෝගීන් සඳහා විශේෂ වෛද්‍යාධාර ගෙවීම', 'நீண்ட கால சிகிச்சை தேவைப்படும் நோயாளிகளுக்கு சிறப்பு மருத்துவ உதவி', 'fa-stethoscope', 'linear-gradient(135deg, #d97706, #f59e0b)', 'Annual training workshops for social workers\nCounseling skill development courses', 'සමාජ සේවකයින් සඳහා වාර්ෂික පුහුණු වැඩමුළු\nඋපදේශන නිපුණතා සංවර්ධන පාඨමාලා', 'சமூகப் பணியாளர்களுக்கான ஆண்டு பயிற்சிப் பட்டறைகள்\nஆலோசனை திறன் மேம்பாட்டு படிப்புகள்', '', '', '', 5),
(6, 'Elders Homes Management', 'වැඩිහිටි නිවාස', 'முதியோர் இல்லங்கள் மேலாண்மை', 'Monitoring and maintaining standards of registered elder care homes', 'වැඩිහිටියන් සඳහා නියමිත ප්‍රමිතියෙන් යුතු වැඩිහිටි නිවාස පවත්වාගෙන යාම', 'முதியோர்களுக்கான தரமான முதியோர் இல்லங்களை நடத்துதல்', 'fa-house-user', 'linear-gradient(135deg, #1e40af, #3b82f6)', '', '', '', '', '', '', 6),
(7, 'Disability Services & Rehabilitation', 'ආබාධිත සේවා', 'மாற்றுத்திறனாளி சேவைகள்', 'Providing rehabilitation, assistive devices, and skills training for disabled people', 'ආබාධ සහිත පුද්ගලයින් සඳහා නිවාස, ආයතන, නිපුණතා මධ්‍යස්ථාන, පුනරුත්ථාපනය', 'மாற்றுத்திறனாளிகளுக்கான மறுவாழ்வு மற்றும் பயிற்சி சேவைகள்', 'fa-wheelchair', 'linear-gradient(135deg, #0e7490, #06b6d4)', '', '', '', '', '', '', 7),
(8, 'Elderly Welfare Schemes', 'වැඩිහිටි සුබසාධන ව්‍යාපෘති', 'முதியோர் நலத் திட்டங்கள்', 'Empowering elderly societies and funding provincial welfare programs', 'වයඹ පළාත තුළ වැඩිහිටි ප්‍රජාව සංවිධානගත කර සුබසාධන ව්‍යාපෘති ක්‍රියාත්මක කිරීම', 'மாகாண முதியோர்களை ஒருங்கிணைத்து நலத் திட்டங்களை செயல்படுத்தல்', 'fa-users', 'linear-gradient(135deg, #7c3aed, #a78bfa)', '', '', '', '', '', '', 8),
(9, 'Maintenance Grants', 'නඩත්තු ආධාර', 'பராமரிப்பு மானியம்', 'Financial support for institutionalized elders or disabled court-ordered dependents', 'අධ්‍යක්ෂ/අධිකරණ නියෝගයකින් නිවාසයක ඇතුළත් කෙරෙන වැඩිහිටියන් හෝ ආබාධිතයන් සඳහා නඩත්තු ආධාර', 'நீதிமன்ற உத்தரவின்படி சேர்க்கப்பட்ட முதியவர்களுக்கான பராமரிப்பு உதவி', 'fa-balance-scale', 'linear-gradient(135deg, #9a3412, #f97316)', '', '', '', '', '', '', 9),
(10, 'Regulated Institutional Supervision', 'ආයතන අධීක්ෂණය', 'நிறுவன கண்காணிப்பு', 'Regulating and inspecting social welfare homes and centers', 'වයඹ පළාතේ වැඩිහිටි හා ආබාධිතයන් සඳහා සේවා සපයන සියලු ආයතනවල අධීක්ෂණය', 'மாகாண முதியோர் மற்றும் மாற்றுத்திறனாளி இல்லங்களை கண்காணித்தல்', 'fa-building', 'linear-gradient(135deg, #374151, #6b7280)', '', '', '', '', '', '', 10);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
