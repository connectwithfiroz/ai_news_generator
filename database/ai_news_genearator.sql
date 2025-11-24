-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 05:51 AM
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
-- Database: `ai_news_genearator`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_22_091241_create_news_table', 2),
(5, '2025_11_22_091458_create_personal_access_tokens_table', 2),
(6, '2025_11_22_091241_create_news_items_table', 3),
(7, '2025_11_22_091241_create_news_mediastack_items_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_items`
--

CREATE TABLE `news_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `summarize_response` text DEFAULT NULL,
  `local_image_path` varchar(255) DEFAULT NULL,
  `original_image_url` varchar(255) DEFAULT NULL,
  `gemini_api_url` varchar(255) DEFAULT NULL,
  `published_at_whatsapp` timestamp NULL DEFAULT NULL,
  `published_url_whatsapp` varchar(255) DEFAULT NULL,
  `published_at_facebook` timestamp NULL DEFAULT NULL,
  `published_url_facebook` varchar(255) DEFAULT NULL,
  `published_at_linkedin` timestamp NULL DEFAULT NULL,
  `published_url_linkedin` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `processed_at` timestamp NULL DEFAULT NULL,
  `batch_no` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_items`
--

INSERT INTO `news_items` (`id`, `requested_at`, `response`, `summarize_response`, `local_image_path`, `original_image_url`, `gemini_api_url`, `published_at_whatsapp`, `published_url_whatsapp`, `published_at_facebook`, `published_url_facebook`, `published_at_linkedin`, `published_url_linkedin`, `is_published`, `processed_at`, `batch_no`, `created_at`, `updated_at`) VALUES
(1, '2025-11-23 10:44:23', '{\"author\":\"TMZ Staff\",\"title\":\"F1 Vegas Grand Prix Drivers, Fans Offered \'Pit Crew Package\' from Nevada Brothel - TMZ\",\"description\":\"As the Formula 1 Grand Prix returns to Las Vegas, the famous Sheri\'s Ranch is rollin\' out an \\\"over-the-top fantasy\\\" bundle for drivers and fans alike!!\",\"url\":\"https:\\/\\/www.tmz.com\\/2025\\/11\\/22\\/f1-vegas-grand-prix-brothel-offer\\/\",\"source\":\"TMZ\",\"image\":\"https:\\/\\/imagez.tmz.com\\/image\\/9c\\/16by9\\/2025\\/11\\/17\\/9ce7e1a82054488f897ace21fe71426a_xl.jpg\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T08:30:42Z\"}', NULL, NULL, 'https://imagez.tmz.com/image/9c/16by9/2025/11/17/9ce7e1a82054488f897ace21fe71426a_xl.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(2, '2025-11-23 10:44:23', '{\"author\":\"Christopher Renstrom\",\"title\":\"Horoscope for Saturday, 11\\/22\\/25 by Christopher Renstrom - SFGATE\",\"description\":\"VIRGO (Aug. 22 - Sept. 21): Are you being uptight? Probably. Take it easy with the self-scrutiny. People aren\'t half as judgmental as you think.\",\"url\":\"https:\\/\\/www.sfgate.com\\/horoscope\\/article\\/horoscope-saturday-11-22-25-christopher-renstrom-21189604.php\",\"source\":\"SFGate\",\"image\":\"https:\\/\\/s.hdnux.com\\/photos\\/47\\/72\\/12\\/10461851\\/6\\/rawImage.jpg\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T06:27:49Z\"}', NULL, NULL, 'https://s.hdnux.com/photos/47/72/12/10461851/6/rawImage.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(3, '2025-11-23 10:44:23', '{\"author\":\"Geno Mrosko\",\"title\":\"SmackDown recap & reactions: This means war - Cageside Seats\",\"description\":\"WWE gets us ready for War Games by making it official in the women\\u2019s match, and even more hype on the men\\u2019s side.\",\"url\":\"https:\\/\\/www.cagesideseats.com\\/wwe\\/398670\\/wwe-smackdown-recap-reactions-nov-21-2025-war-games-womens-match-official-lynch-lee\",\"source\":\"Cageside Seats\",\"image\":\"https:\\/\\/platform.cagesideseats.com\\/wp-content\\/uploads\\/sites\\/54\\/2025\\/11\\/gettyimages-2247262282.jpg?quality=90&strip=all&crop=0%2C10.852336924521%2C100%2C78.295326150957&w=1200\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T06:00:00Z\"}', NULL, NULL, 'https://platform.cagesideseats.com/wp-content/uploads/sites/54/2025/11/gettyimages-2247262282.jpg?quality=90&strip=all&crop=0%2C10.852336924521%2C100%2C78.295326150957&w=1200', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(4, '2025-11-23 10:44:23', '{\"author\":\"Natalie Oganesyan\",\"title\":\"Kevin Spacey Says He\\u2019s Homeless, Living In Hotels & Airbnbs While Working As A Lounge Singer In Cyprus - Deadline\",\"description\":\"Kevin Spacey said he \'literally\' has no \'home,\' is living out of his suitcase in various hotels and Airbnbs while working as a lounge singer in Cyprus\",\"url\":\"http:\\/\\/deadline.com\\/2025\\/11\\/kevin-spacey-homeless-hotel-airbnb-singer-cyprus-1236626319\\/\",\"source\":\"Deadline\",\"image\":\"https:\\/\\/deadline.com\\/wp-content\\/uploads\\/2024\\/07\\/GettyImages-1556624476.jpg?w=1024\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T03:00:00Z\"}', NULL, NULL, 'https://deadline.com/wp-content/uploads/2024/07/GettyImages-1556624476.jpg?w=1024', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(5, '2025-11-23 10:44:23', '{\"author\":\"Rachel Dillin\",\"title\":\"Bold and Beautiful Casting Shocker: Luna Dies as Lisa Yamada Wraps Her Emmy-Winning Run - Soap Hub\",\"description\":\"Get the details.\",\"url\":\"https:\\/\\/soaphub.com\\/the-bold-and-the-beautiful\\/the-bold-and-the-beautiful-news\\/lisa-yamada-luna-exit-nov-21\\/\",\"source\":\"Soap Hub\",\"image\":\"https:\\/\\/soaphub.com\\/wp-content\\/uploads\\/2025\\/11\\/bold-and-the-beautiful-lisa-yamada-out-as-luna.jpg\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-21T23:46:33Z\"}', NULL, NULL, 'https://soaphub.com/wp-content/uploads/2025/11/bold-and-the-beautiful-lisa-yamada-out-as-luna.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(6, '2025-11-23 10:44:23', '{\"author\":null,\"title\":\"Rian Johnson says AI makes \\\"Everything worse in every conceivable way\\\" - AV Club\",\"description\":\"Rian Johnson says AI makes \\\"Everything worse in every conceivable way\\\"\",\"url\":\"https:\\/\\/www.avclub.com\\/rian-johnson-ai-everything-worse\",\"source\":\"The A.V. Club\",\"image\":\"https:\\/\\/img.pastemagazine.com\\/wp-content\\/avuploads\\/2025\\/11\\/21165506\\/johnson-wake-up.jpg\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-21T22:59:00Z\"}', NULL, NULL, 'https://img.pastemagazine.com/wp-content/avuploads/2025/11/21165506/johnson-wake-up.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(7, '2025-11-23 10:44:23', '{\"author\":\"Samantha Chery\",\"title\":\"Fugees\\u2019 Pras Mich\\u00e9l sentenced to 14 years over illegal Obama donations - The Washington Post\",\"description\":\"The former hip-hop rapper was convicted on 10 federal charges over his role in helping a Malaysian financier get a photo with Barack Obama.\",\"url\":\"https:\\/\\/www.washingtonpost.com\\/style\\/2025\\/11\\/21\\/pras-michel-fugees-sentencing-obama\\/\",\"source\":\"The Washington Post\",\"image\":\"https:\\/\\/www.washingtonpost.com\\/wp-apps\\/imrs.php?src=https:\\/\\/arc-anglerfish-washpost-prod-washpost.s3.amazonaws.com\\/public\\/ZSBZHCG5M4I63J4OTJ6CIGFQBQ.jpg&w=1440\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-21T22:36:55Z\"}', NULL, NULL, 'https://www.washingtonpost.com/wp-apps/imrs.php?src=https://arc-anglerfish-washpost-prod-washpost.s3.amazonaws.com/public/ZSBZHCG5M4I63J4OTJ6CIGFQBQ.jpg&w=1440', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(8, '2025-11-23 10:44:23', '{\"author\":\"Clayton Davis\",\"title\":\"\\u2018Wicked: For Good\\u2019 Director Jon M. Chu Says Ariana Grande and Cynthia Erivo Created the Most Emotional Scene by Accident: \\u2018I Forgot to Call Cut\\u2019 - Variety\",\"description\":\"\\u2018Wicked: For Good\\u2019 director Jon M. Chu details the improvised Cynthia Erivo and Ariana Grande scene, and how it changed everything.\",\"url\":\"https:\\/\\/variety.com\\/2025\\/film\\/awards\\/jon-m-chu-wicked-for-good-spoilers-ending-explained-1236587506\\/\",\"source\":\"Variety\",\"image\":\"https:\\/\\/variety.com\\/wp-content\\/uploads\\/2025\\/11\\/MCDWIFO_UV006.jpg?crop=0px%2C275px%2C2997px%2C1688px&resize=1000%2C563\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-21T21:30:00Z\"}', NULL, NULL, 'https://variety.com/wp-content/uploads/2025/11/MCDWIFO_UV006.jpg?crop=0px%2C275px%2C2997px%2C1688px&resize=1000%2C563', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(9, '2025-11-23 10:44:23', '{\"author\":\"McKinley Franklin\",\"title\":\"Kristen Bell, Brian Cox Say 2010 Audio Has Been Repurposed for Fox News Podcast Without Their Knowledge - The Hollywood Reporter\",\"description\":\"The network revealed the new podcast on Wednesday, with both Bell and Cox attached to voice Biblical figures, as Sean Astin and Neal McDonough are also listed as part of the cast.\",\"url\":\"http:\\/\\/www.hollywoodreporter.com\\/news\\/general-news\\/kristen-bell-brian-cox-life-jesus-podcast-fox-news-1236433343\\/\",\"source\":\"Hollywood Reporter\",\"image\":\"https:\\/\\/www.hollywoodreporter.com\\/wp-content\\/uploads\\/2025\\/11\\/Kristen-Bell-and-Brian-Cox-Split-Getty-H-2025.jpg?w=1296&h=730&crop=1\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-21T21:19:51Z\"}', NULL, NULL, 'https://www.hollywoodreporter.com/wp-content/uploads/2025/11/Kristen-Bell-and-Brian-Cox-Split-Getty-H-2025.jpg?w=1296&h=730&crop=1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23'),
(10, '2025-11-23 10:44:23', '{\"author\":\"Steven J. Horowitz\",\"title\":\"Olivia Dean Calls Out Live Nation, Ticketmaster Over Resale Prices - Variety\",\"description\":\"Olivia Dean called out Live Nation, Ticketmaster and AEG over inflated resale prices for tickets to her tour.\",\"url\":\"https:\\/\\/variety.com\\/2025\\/music\\/news\\/olivia-dean-blasts-live-nation-ticketmaster-resale-prices-1236588706\\/\",\"source\":\"Variety\",\"image\":\"https:\\/\\/variety.com\\/wp-content\\/uploads\\/2025\\/11\\/GettyImages-2247412850.jpg?w=1000&h=563&crop=1\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-21T21:11:00Z\"}', NULL, NULL, 'https://variety.com/wp-content/uploads/2025/11/GettyImages-2247412850.jpg?w=1000&h=563&crop=1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-23 10:44:23', '2025-11-23 10:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `news_items_mediastack`
--

CREATE TABLE `news_items_mediastack` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `summarize_response` text DEFAULT NULL,
  `local_image_path` varchar(255) DEFAULT NULL,
  `original_image_url` varchar(255) DEFAULT NULL,
  `gemini_api_url` varchar(255) DEFAULT NULL,
  `published_at_whatsapp` timestamp NULL DEFAULT NULL,
  `published_url_whatsapp` varchar(255) DEFAULT NULL,
  `published_at_facebook` timestamp NULL DEFAULT NULL,
  `published_url_facebook` varchar(255) DEFAULT NULL,
  `published_at_linkedin` timestamp NULL DEFAULT NULL,
  `published_url_linkedin` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `processed_at` timestamp NULL DEFAULT NULL,
  `batch_no` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_items_mediastack`
--

INSERT INTO `news_items_mediastack` (`id`, `requested_at`, `response`, `summarize_response`, `local_image_path`, `original_image_url`, `gemini_api_url`, `published_at_whatsapp`, `published_url_whatsapp`, `published_at_facebook`, `published_url_facebook`, `published_at_linkedin`, `published_url_linkedin`, `is_published`, `processed_at`, `batch_no`, `created_at`, `updated_at`) VALUES
(1, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Telangana Culinary Heritage Walk explores Hyderabad\\u2019s food culture, iconic eateries\",\"description\":\"Telangana Culinary Heritage Walk explores Hyderabad\\u2019s food culture, iconic eateries\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/cities\\/Hyderabad\\/telangana-culinary-heritage-walk-explores-hyderabads-food-culture-iconic-eateries\\/article70311303.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:20:00+00:00\"}', 'तेलंगाना में एक खास सैर हो रही है। लोग हैदराबाद शहर में पैदल चलकर पुरानी और मशहूर खाने की जगहें देख रहे हैं। इस सैर से उन्हें हैदराबाद के खाने-पीने के तरीकों और उसकी पुरानी पहचान को समझने का मौका मिलेगा।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:17:15', 1, '2025-11-22 08:20:30', '2025-11-23 03:17:15'),
(2, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"With no opposition, LDF secures four wins in Kannur\",\"description\":\"CPI(M) bags two seats in Anthoor municipality and two in Malapattam grama panchayat. DCC president says UDF candidates\\u2019 nominations were rejected to help the LDF\\u2019s cause\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/kerala\\/with-no-opposition-ldf-secures-four-wins-in-kannur\\/article70310723.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:18:14+00:00\"}', 'कन्नूर में LDF पार्टी ने बिना किसी मुकाबले के चार सीटें जीत ली हैं। इसमें से CPI(M) पार्टी ने अंथूर और मलपट्टम में दो-दो सीटें जीतीं। DCC के अध्यक्ष का कहना है कि UDF के उम्मीदवारों के नाम जानबूझकर काट दिए गए ताकि LDF को फायदा हो।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:22:14', 1, '2025-11-22 08:20:30', '2025-11-23 03:22:14'),
(3, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Madya Nisheda Andolana Karnataka to hold protest in Bengaluru on Nov. 25 against illegal liquor sales\",\"description\":\"Madya Nisheda Andolana Karnataka to hold protest in Bengaluru on Nov. 25 against illegal liquor sales\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/karnataka\\/madya-nisheda-andolana-karnataka-to-hold-protest-in-bengaluru-on-nov-25-against-illegal-liquor-sales\\/article70311202.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:09:35+00:00\"}', 'मद्य निषेध आंदोलन कर्नाटक नाम का एक समूह 25 नवंबर को बेंगलुरु में विरोध प्रदर्शन करेगा। यह प्रदर्शन गलत तरीके से बेची जा रही शराब (अवैध शराब) की बिक्री रोकने के लिए किया जाएगा।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:22:22', 1, '2025-11-22 08:20:30', '2025-11-23 03:22:22'),
(4, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Union government approves heritage mission for Kumaranalloor manuscripts\",\"description\":\"Among the 14 research institutions approved by the Mission across India, Sevadhi has been classified as an independent research institute\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/kerala\\/union-government-approves-heritage-mission-for-kumaranalloor-manuscripts\\/article70311259.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:09:35+00:00\"}', 'केंद्र सरकार ने कुमरनल्लूर की पुरानी पोथियों (हाथ से लिखी किताबों) को सँवारने और बचाने के लिए एक खास काम को मंज़ूरी दी है। पूरे देश में इस काम के लिए कुल 14 संस्थाएं चुनी गई हैं, और इनमें से सेवाधि नाम की संस्था को एक अलग और महत्वपूर्ण दर्जा मिला है।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:22:31', 1, '2025-11-22 08:20:30', '2025-11-23 03:22:31'),
(5, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Man arrested for selling painkiller tablets as drugs to students\",\"description\":\"Man arrested for selling painkiller tablets as drugs to students\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/karnataka\\/man-arrested-for-selling-painkiller-tablets-as-drugs-to-students\\/article70311082.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:06:19+00:00\"}', 'ज़रूर, चूँकि यहाँ कोई विषय नहीं दिया गया है, मैं साफ-सफाई और सेहत के महत्व पर एक आसान सारांश लिख रहा हूँ, जो ग्रामीण इलाकों में पढ़ा जाने वाला एक आम विषय है:\n\n---\n\n**सेहत और साफ-सफाई**\n\nबीमारी से बचने और तंदुरुस्त रहने के लिए साफ-सफाई रखना बहुत ज़रूरी है। अपने हाथ साबुन और पानी से खूब अच्छे से धोएँ। खासकर खाना खाने से पहले और शौच के बाद हाथ धोना न भूलें। ऐसा करने से कीटाणु नहीं फैलते और आप व आपका परिवार बीमारियों से दूर रहते हैं। घर और आस-पास साफ रखने से भी सेहत अच्छी रहती है। याद रखें, साफ-सफाई से ही अच्छी सेहत मिलती है।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-23 06:14:14'),
(6, '2025-11-22 08:20:30', '{\"author\":\"Tahir Qureshi\",\"title\":\"10 countries with the most difficult education system, and where does India stand\",\"description\":\"In many countries, competitive exams are so challenging that years of hard work fall short.\",\"url\":\"https:\\/\\/www.india.com\\/education-3\\/10-countries-in-the-world-with-the-most-difficult-education-systems-students-school-south-korea-japan-china-singapore-finland-russia-india-hong-kong-switzerland-usa-8196160\\/\",\"source\":\"India.com\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:01:00+00:00\"}', 'यह जानकारी दुनिया के उन 10 देशों के बारे में है जहाँ की पढ़ाई सबसे मुश्किल मानी जाती है। इन देशों में इम्तिहान इतने कठिन होते हैं कि सालों की कड़ी मेहनत भी कम पड़ जाती है। इस लेख में यह भी बताया जाएगा कि भारत में पढ़ाई कितनी मुश्किल है।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-23 07:03:22'),
(7, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Kurnool SP tells police to tighten vigil after Maoists call for Bharat Bandh\",\"description\":\"Police personnel have been instructed to identify the stretches ridden with potholes and dangerous curves and carry out the necessary repairs\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/andhra-pradesh\\/kurnool-sp-tells-police-to-tighten-vigil-after-maoists-call-for-bharat-bandh\\/article70311211.ece\",\"source\":\"The Hindu\",\"image\":\"https:\\/\\/th-i.thgim.com\\/public\\/incoming\\/evc2zz\\/article70311313.ece\\/alternates\\/LANDSCAPE_1200\\/10215_1_2_2025_15_59_5_1_IMG_20250201_WA0027.JPG\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:51:26+00:00\"}', NULL, NULL, 'https://th-i.thgim.com/public/incoming/evc2zz/article70311313.ece/alternates/LANDSCAPE_1200/10215_1_2_2025_15_59_5_1_IMG_20250201_WA0027.JPG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-22 08:20:30'),
(8, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Partial cancellation of Chennai-bound trains on November 23\",\"description\":\"Partial cancellation of Chennai-bound trains on November 23\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/karnataka\\/partial-cancellation-of-chennai-bound-trains-on-november-23\\/article70311246.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:47:17+00:00\"}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-22 08:20:30'),
(9, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Kanimozhi inaugurates TNUHDB apartments at Ayyanadaippu village in Thoothukudi\",\"description\":\"Kanimozhi inaugurates TNUHDB apartments at Ayyanadaippu village in Thoothukudi\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/cities\\/Madurai\\/kanimozhi-inaugurates-tnuhdb-apartments-at-ayyanadaippu-village-in-thoothukudi\\/article70311136.ece\",\"source\":\"The Hindu\",\"image\":\"https:\\/\\/th-i.thgim.com\\/public\\/incoming\\/9ar2k7\\/article70311285.ece\\/alternates\\/LANDSCAPE_1200\\/2315_22_11_2025_16_36_15_1_THOOTHUKUDI_16.JPG\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:41:59+00:00\"}', '', NULL, 'https://th-i.thgim.com/public/incoming/9ar2k7/article70311285.ece/alternates/LANDSCAPE_1200/2315_22_11_2025_16_36_15_1_THOOTHUKUDI_16.JPG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-23 06:34:17'),
(10, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Buy or sell: Sumeet Bagadia recommends three stocks to buy on Monday \\u2014 24 November 2025 - livemint.com\",\"description\":\"Buy or sell: Sumeet Bagadia recommends three stocks to buy on Monday \\u2014 24 November 2025&nbsp;&nbsp;livemint.comBreakout stocks to buy or sell: Sumeet Bagadia recommends three shares to buy today \\u2014 21 November 2025&nbsp;&nbsp;livemint.comTop stocks to buy today: Stock recommendations for November 21, 2025 - check list&nbsp;&nbsp;Times of IndiaTrade Spotlight: How should you trade HDFC Life, Tata Communications, Jubilant Ingrevia, Cummins India,...&nbsp;&nbsp;MoneycontrolNifty 50, Sensex today: What to expect from Indian stock market in trade on November 21 after Nasdaq, Nikkei sell-off&nbsp...\",\"url\":\"https:\\/\\/news.google.com\\/rss\\/articles\\/CBMi6gFBVV95cUxQZTJCNXEzSzQ2NHk3RDA5MmtBcVNQWmVfMXFYWHhiTGpGVmF6TThraTFBUjZkRi10V2lYN2wzbWZ5LW4xWGVST2JhU0NxMjdMLWc5dVJ1N1NTYjFTcjdNczFFSDNib180WDBOc3Z1RUktanp3MWpoLWVvajR0bFZIczRjeEY1ZnVQRUN6OW9ERHlWdHBEWmZ1d0NJakFHcjZHYzNXaEh5MXUyVmtGRnZma2pLRHJBenNZaG5jUjY1NUJfMmhRZ1ZKOHZvM3R3WHFrSmpJcUtoMFZITEkwY0FYQzl2WGhsOWdFVVHSAe8BQVVfeXFMTVFHQWdRNUZMaC1Nbk5PVnBSb0tjVFE4U0lLdG1YRDNzYmpMdndlVTdTZERVT1BKQTdydUNmbXEtelBTRzJxTjRQUE9aTmJCVTVxRDRIajVFS3BJUkpWV1FPaDlncVItMnptb3BqRlNHVFdiSkZBN1JnTHppak56UjJKSkxBaGVVek5lY1R5ajd6NndWdVd4MUJER09HRVdyZDF6Y2lGYlg4VWdBTkVnUVNGY21EOFZlMGFZYU9ZeV9EMldFeWJYSGtSV2ZrUnB5R0gzRGFxQXJyZ243Sm1aTUNldVBpTmd5dDM3N0xINVk?oc=5\",\"source\":\"Google News Business IN\",\"image\":null,\"category\":\"business\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:39:28+00:00\"}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-22 08:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `news_mediastack_items`
--

CREATE TABLE `news_mediastack_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `summarize_response` text DEFAULT NULL,
  `local_image_path` varchar(255) DEFAULT NULL,
  `original_image_url` varchar(255) DEFAULT NULL,
  `gemini_api_url` varchar(255) DEFAULT NULL,
  `published_at_whatsapp` timestamp NULL DEFAULT NULL,
  `published_url_whatsapp` varchar(255) DEFAULT NULL,
  `published_at_facebook` timestamp NULL DEFAULT NULL,
  `published_url_facebook` varchar(255) DEFAULT NULL,
  `published_at_linkedin` timestamp NULL DEFAULT NULL,
  `published_url_linkedin` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `processed_at` timestamp NULL DEFAULT NULL,
  `batch_no` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_mediastack_items`
--

INSERT INTO `news_mediastack_items` (`id`, `requested_at`, `response`, `summarize_response`, `local_image_path`, `original_image_url`, `gemini_api_url`, `published_at_whatsapp`, `published_url_whatsapp`, `published_at_facebook`, `published_url_facebook`, `published_at_linkedin`, `published_url_linkedin`, `is_published`, `processed_at`, `batch_no`, `created_at`, `updated_at`) VALUES
(1, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Telangana Culinary Heritage Walk explores Hyderabad\\u2019s food culture, iconic eateries\",\"description\":\"Telangana Culinary Heritage Walk explores Hyderabad\\u2019s food culture, iconic eateries\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/cities\\/Hyderabad\\/telangana-culinary-heritage-walk-explores-hyderabads-food-culture-iconic-eateries\\/article70311303.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:20:00+00:00\"}', 'तेलंगाना में एक खास सैर हो रही है। लोग हैदराबाद शहर में पैदल चलकर पुरानी और मशहूर खाने की जगहें देख रहे हैं। इस सैर से उन्हें हैदराबाद के खाने-पीने के तरीकों और उसकी पुरानी पहचान को समझने का मौका मिलेगा।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:17:15', 1, '2025-11-22 08:20:30', '2025-11-23 03:17:15'),
(2, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"With no opposition, LDF secures four wins in Kannur\",\"description\":\"CPI(M) bags two seats in Anthoor municipality and two in Malapattam grama panchayat. DCC president says UDF candidates\\u2019 nominations were rejected to help the LDF\\u2019s cause\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/kerala\\/with-no-opposition-ldf-secures-four-wins-in-kannur\\/article70310723.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:18:14+00:00\"}', 'कन्नूर में LDF पार्टी ने बिना किसी मुकाबले के चार सीटें जीत ली हैं। इसमें से CPI(M) पार्टी ने अंथूर और मलपट्टम में दो-दो सीटें जीतीं। DCC के अध्यक्ष का कहना है कि UDF के उम्मीदवारों के नाम जानबूझकर काट दिए गए ताकि LDF को फायदा हो।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:22:14', 1, '2025-11-22 08:20:30', '2025-11-23 03:22:14'),
(3, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Madya Nisheda Andolana Karnataka to hold protest in Bengaluru on Nov. 25 against illegal liquor sales\",\"description\":\"Madya Nisheda Andolana Karnataka to hold protest in Bengaluru on Nov. 25 against illegal liquor sales\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/karnataka\\/madya-nisheda-andolana-karnataka-to-hold-protest-in-bengaluru-on-nov-25-against-illegal-liquor-sales\\/article70311202.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:09:35+00:00\"}', 'मद्य निषेध आंदोलन कर्नाटक नाम का एक समूह 25 नवंबर को बेंगलुरु में विरोध प्रदर्शन करेगा। यह प्रदर्शन गलत तरीके से बेची जा रही शराब (अवैध शराब) की बिक्री रोकने के लिए किया जाएगा।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:22:22', 1, '2025-11-22 08:20:30', '2025-11-23 03:22:22'),
(4, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Union government approves heritage mission for Kumaranalloor manuscripts\",\"description\":\"Among the 14 research institutions approved by the Mission across India, Sevadhi has been classified as an independent research institute\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/kerala\\/union-government-approves-heritage-mission-for-kumaranalloor-manuscripts\\/article70311259.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:09:35+00:00\"}', 'केंद्र सरकार ने कुमरनल्लूर की पुरानी पोथियों (हाथ से लिखी किताबों) को सँवारने और बचाने के लिए एक खास काम को मंज़ूरी दी है। पूरे देश में इस काम के लिए कुल 14 संस्थाएं चुनी गई हैं, और इनमें से सेवाधि नाम की संस्था को एक अलग और महत्वपूर्ण दर्जा मिला है।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-23 03:22:31', 1, '2025-11-22 08:20:30', '2025-11-23 03:22:31'),
(5, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Man arrested for selling painkiller tablets as drugs to students\",\"description\":\"Man arrested for selling painkiller tablets as drugs to students\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/karnataka\\/man-arrested-for-selling-painkiller-tablets-as-drugs-to-students\\/article70311082.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:06:19+00:00\"}', 'ज़रूर, चूँकि यहाँ कोई विषय नहीं दिया गया है, मैं साफ-सफाई और सेहत के महत्व पर एक आसान सारांश लिख रहा हूँ, जो ग्रामीण इलाकों में पढ़ा जाने वाला एक आम विषय है:\n\n---\n\n**सेहत और साफ-सफाई**\n\nबीमारी से बचने और तंदुरुस्त रहने के लिए साफ-सफाई रखना बहुत ज़रूरी है। अपने हाथ साबुन और पानी से खूब अच्छे से धोएँ। खासकर खाना खाने से पहले और शौच के बाद हाथ धोना न भूलें। ऐसा करने से कीटाणु नहीं फैलते और आप व आपका परिवार बीमारियों से दूर रहते हैं। घर और आस-पास साफ रखने से भी सेहत अच्छी रहती है। याद रखें, साफ-सफाई से ही अच्छी सेहत मिलती है।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-23 06:14:14'),
(6, '2025-11-22 08:20:30', '{\"author\":\"Tahir Qureshi\",\"title\":\"10 countries with the most difficult education system, and where does India stand\",\"description\":\"In many countries, competitive exams are so challenging that years of hard work fall short.\",\"url\":\"https:\\/\\/www.india.com\\/education-3\\/10-countries-in-the-world-with-the-most-difficult-education-systems-students-school-south-korea-japan-china-singapore-finland-russia-india-hong-kong-switzerland-usa-8196160\\/\",\"source\":\"India.com\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T13:01:00+00:00\"}', 'यह जानकारी दुनिया के उन 10 देशों के बारे में है जहाँ की पढ़ाई सबसे मुश्किल मानी जाती है। इन देशों में इम्तिहान इतने कठिन होते हैं कि सालों की कड़ी मेहनत भी कम पड़ जाती है। इस लेख में यह भी बताया जाएगा कि भारत में पढ़ाई कितनी मुश्किल है।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-23 07:03:22'),
(7, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Kurnool SP tells police to tighten vigil after Maoists call for Bharat Bandh\",\"description\":\"Police personnel have been instructed to identify the stretches ridden with potholes and dangerous curves and carry out the necessary repairs\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/andhra-pradesh\\/kurnool-sp-tells-police-to-tighten-vigil-after-maoists-call-for-bharat-bandh\\/article70311211.ece\",\"source\":\"The Hindu\",\"image\":\"https:\\/\\/th-i.thgim.com\\/public\\/incoming\\/evc2zz\\/article70311313.ece\\/alternates\\/LANDSCAPE_1200\\/10215_1_2_2025_15_59_5_1_IMG_20250201_WA0027.JPG\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:51:26+00:00\"}', NULL, NULL, 'https://th-i.thgim.com/public/incoming/evc2zz/article70311313.ece/alternates/LANDSCAPE_1200/10215_1_2_2025_15_59_5_1_IMG_20250201_WA0027.JPG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-22 08:20:30'),
(8, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Partial cancellation of Chennai-bound trains on November 23\",\"description\":\"Partial cancellation of Chennai-bound trains on November 23\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/national\\/karnataka\\/partial-cancellation-of-chennai-bound-trains-on-november-23\\/article70311246.ece\",\"source\":\"The Hindu\",\"image\":null,\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:47:17+00:00\"}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-22 08:20:30'),
(9, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Kanimozhi inaugurates TNUHDB apartments at Ayyanadaippu village in Thoothukudi\",\"description\":\"Kanimozhi inaugurates TNUHDB apartments at Ayyanadaippu village in Thoothukudi\",\"url\":\"https:\\/\\/www.thehindu.com\\/news\\/cities\\/Madurai\\/kanimozhi-inaugurates-tnuhdb-apartments-at-ayyanadaippu-village-in-thoothukudi\\/article70311136.ece\",\"source\":\"The Hindu\",\"image\":\"https:\\/\\/th-i.thgim.com\\/public\\/incoming\\/9ar2k7\\/article70311285.ece\\/alternates\\/LANDSCAPE_1200\\/2315_22_11_2025_16_36_15_1_THOOTHUKUDI_16.JPG\",\"category\":\"general\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:41:59+00:00\"}', '', NULL, 'https://th-i.thgim.com/public/incoming/9ar2k7/article70311285.ece/alternates/LANDSCAPE_1200/2315_22_11_2025_16_36_15_1_THOOTHUKUDI_16.JPG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-23 06:34:17'),
(10, '2025-11-22 08:20:30', '{\"author\":null,\"title\":\"Buy or sell: Sumeet Bagadia recommends three stocks to buy on Monday \\u2014 24 November 2025 - livemint.com\",\"description\":\"Buy or sell: Sumeet Bagadia recommends three stocks to buy on Monday \\u2014 24 November 2025&nbsp;&nbsp;livemint.comBreakout stocks to buy or sell: Sumeet Bagadia recommends three shares to buy today \\u2014 21 November 2025&nbsp;&nbsp;livemint.comTop stocks to buy today: Stock recommendations for November 21, 2025 - check list&nbsp;&nbsp;Times of IndiaTrade Spotlight: How should you trade HDFC Life, Tata Communications, Jubilant Ingrevia, Cummins India,...&nbsp;&nbsp;MoneycontrolNifty 50, Sensex today: What to expect from Indian stock market in trade on November 21 after Nasdaq, Nikkei sell-off&nbsp...\",\"url\":\"https:\\/\\/news.google.com\\/rss\\/articles\\/CBMi6gFBVV95cUxQZTJCNXEzSzQ2NHk3RDA5MmtBcVNQWmVfMXFYWHhiTGpGVmF6TThraTFBUjZkRi10V2lYN2wzbWZ5LW4xWGVST2JhU0NxMjdMLWc5dVJ1N1NTYjFTcjdNczFFSDNib180WDBOc3Z1RUktanp3MWpoLWVvajR0bFZIczRjeEY1ZnVQRUN6OW9ERHlWdHBEWmZ1d0NJakFHcjZHYzNXaEh5MXUyVmtGRnZma2pLRHJBenNZaG5jUjY1NUJfMmhRZ1ZKOHZvM3R3WHFrSmpJcUtoMFZITEkwY0FYQzl2WGhsOWdFVVHSAe8BQVVfeXFMTVFHQWdRNUZMaC1Nbk5PVnBSb0tjVFE4U0lLdG1YRDNzYmpMdndlVTdTZERVT1BKQTdydUNmbXEtelBTRzJxTjRQUE9aTmJCVTVxRDRIajVFS3BJUkpWV1FPaDlncVItMnptb3BqRlNHVFdiSkZBN1JnTHppak56UjJKSkxBaGVVek5lY1R5ajd6NndWdVd4MUJER09HRVdyZDF6Y2lGYlg4VWdBTkVnUVNGY21EOFZlMGFZYU9ZeV9EMldFeWJYSGtSV2ZrUnB5R0gzRGFxQXJyZ243Sm1aTUNldVBpTmd5dDM3N0xINVk?oc=5\",\"source\":\"Google News Business IN\",\"image\":null,\"category\":\"business\",\"language\":\"en\",\"country\":\"in\",\"published_at\":\"2025-11-22T12:39:28+00:00\"}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-11-22 08:20:30', '2025-11-22 08:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('DaGkQUysPdWtjHV8x2HLWSSgjGb2lshp3HndwZU2', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUG02Q0FmNWQ0M3k3WWlGcHVRNDZwMmEyUUpydE5ROUpWa09HZ2VWeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJC82OXN6emYuM1guMnNhRU43aDBhek9kQ3NLWnVuVDh0Y090TTRjNjRLaDdJZTA1SHhsSmJtIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0OToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL25ld3MtbWVkaWFzdGFjay1pdGVtcyI7czo1OiJyb3V0ZSI7czo1MjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLm5ld3MtbWVkaWFzdGFjay1pdGVtcy5pbmRleCI7fX0=', 1763917898);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Firoz', 'contacttofiroz@gmail.com', NULL, '$2y$12$GjJZZ6WYRfIIRewCpw6OjOYeB87/i3sdsbtodsGPun1lgRnZNv8Lu', NULL, '2025-11-22 03:41:21', '2025-11-22 03:41:21'),
(2, 'Firoz', 'firoz@test.com', NULL, '$2y$12$/69szzf.3X.2saEN7h0azOdCsKZunT8tcOtM4c64Kh7Ie05HxlJbm', 'iRt22xBxRn4N88DVAG3zM5tgwmEBEpFWHx1XSWgG4njcJTpoTR6SKI7ISY6d', '2025-11-23 05:33:12', '2025-11-23 05:33:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_items`
--
ALTER TABLE `news_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_items_mediastack`
--
ALTER TABLE `news_items_mediastack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_mediastack_items`
--
ALTER TABLE `news_mediastack_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_items`
--
ALTER TABLE `news_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news_items_mediastack`
--
ALTER TABLE `news_items_mediastack`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news_mediastack_items`
--
ALTER TABLE `news_mediastack_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
