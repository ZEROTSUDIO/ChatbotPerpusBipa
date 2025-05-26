-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Bulan Mei 2025 pada 14.53
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatbotbipa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `user_message` text DEFAULT NULL,
  `bot_response` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `chats`
--

INSERT INTO `chats` (`id`, `user`, `user_message`, `bot_response`, `timestamp`) VALUES
(388, 8, 'halo selamat pagi', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-15 17:10:20'),
(389, 8, 'assalamualaikum wr wb', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-15 17:10:36'),
(390, 8, 'Terima kasih atas informasinya', '<p><strong>Sampai jumpa, Sir!</strong> Semoga harimu penuh pengetahuan dan inspirasi. ??</p>', '2025-05-15 17:11:12'),
(391, 8, 'aku pergi', '<p><strong>Sampai jumpa, Sir!</strong> Semoga harimu penuh pengetahuan dan inspirasi. ??</p>', '2025-05-15 17:11:35'),
(392, 8, 'Mau tanya cara daftar anggota bagaimana ya', '<p><strong>?? Informasi Keanggotaan:</strong></p><ol><li>Mengisi formulir pendaftaran online/di tempat</li><li>Melampirkan fotokopi KTP/KTM</li><li>Menyerahkan 2 lembar pas foto 3x4</li></ol><p>Setelah data diverifikasi, kartu anggota akan dikirimkan via email/diambil langsung. Kartu ini memungkinkan Anda meminjam buku fisik dan mengakses koleksi digital.</p><p>Bagi mahasiswa dari luar STMIK BINA PATRIA MAGELANG diperbolehkan memanfaatkan fasilitas perpustakaan, kecuali peminjaman buku. Syarat untuk memanfaatkan fasilitas adalah dengan menunjukkan kartu mahasiswa yang masih berlaku.</p>', '2025-05-15 17:11:57'),
(393, 8, 'cara memimjam buku bagaimana ya kak', '<p><strong>?? Prosedur Peminjaman & Pengembalian Buku:</strong></p><p><em>Cara Peminjaman:</em></p><ol><li>Cari buku yang diinginkan melalui sistem <em>search</em> atau katalog.</li><li>Pastikan buku tersedia (status: Tersedia).</li><li>Datang ke meja sirkulasi dengan membawa kartu anggota.</li><li>Serahkan kartu anggota dan beri tahu petugas judul buku.</li><li>Buku akan diproses, kemudian Anda menerima slip peminjaman beserta tanggal kembali.</li></ol><p><em>Aturan Peminjaman:</em></p><ul><li>Maksimum 3 buku per anggota.</li><li>Durasi peminjaman: 7 hari kerja.</li><li>Perpanjangan bisa dilakukan 1 kali jika belum ada pemesan.</li></ul><p><em>Prosedur Pengembalian:</em></p><ol><li>Kembalikan buku ke meja sirkulasi atau <em>drop box</em> setelah jam layanan.</li><li>Petugas akan memeriksa kondisi buku dan menghapus catatan peminjaman.</li><li>Jika terlambat, denda Rp1.000/hari/buku akan dikenakan.</li></ol><p>Semoga membantu, Sir! ??</p>', '2025-05-15 17:13:21'),
(394, 8, 'fasilitas perpus apa saja ya', '<p><strong> Daftar Fasilitas:</p></strong><ol><li>Peminjaman buku teks, 2 eksemplar selama 7 hari kerja, dengan denda keterlambatan 200 per hari per eksemplar</li><li>Baca di tempat</li><li>Penelusuran literatur</li><li>Fotokopi koleksi</li><li>Ruang baca</li><li>Internet Gratis</li><li>Locker tas/jaket</li></ol><p>Bagi mahasiswa dari luar STMIK BINA PATRIA MAGELANG diperbolehkan memanfaatkan fasilitas perpustakaan, kecuali peminjaman buku. Syarat untuk memanfaatkan fasilitas adalah dengan menunjukkan kartu mahasiswa yang masih berlaku.</p>', '2025-05-15 17:14:08'),
(395, 8, 'hari apa saja buka layanannya', '<p><strong>?? Jam Layanan Perpustakaan:</strong></p><ul><li><strong>Senin - Kamis</strong>: 08.00 - 15.30</li><li><strong>Jumat & Sabtu</strong>: 08.00 - 18.00</li><li><strong>Minggu</strong>: 08.00 - 12.00</li></ul>', '2025-05-15 17:14:37'),
(396, 8, 'carikan buku ', '<p><strong>?? Pencarian Buku:</strong><br>Ketik judul, pengarang, atau kata kunci buku yang Anda cari.<br><em>Contoh:</em> cari buku \"Pemrograman Python untuk Pemula\"</p>', '2025-05-15 17:14:51'),
(397, 8, 'python', '<strong>Buku yang paling relevan untuk Anda:</strong><br><br><div class=\'book-recommendation\'><strong>1. Python in a Nutshell</strong><br>Penulis: By Martelli, Alex<br>Kategori: Computers , Programming Languages , General<br>Tahun: 2003<br><p><em>Deskripsi:</em> Ask any Python aficionado and you\'ll hear that Python programmers have it all: an elegant language that offers object-oriented programming support, a readable, maintainable syntax, integration with...</p>Relevansi: 54%<br></div><br><details><summary>???? Lihat rekomendasi tambahan</summary><br><div class=\'book-recommendation\'><strong>2. Numerical Recipes in C</strong><br>Penulis: By Teukolsky, Saul A., Flannery, Brian P., Press, William H., and Vetterling, T. W.<br>Kategori: Mathematics , General<br>Tahun: 1988<br><p><em>Deskripsi:</em> Provides nearly 200 computer routines which enable scientists to utilize the C language for a variety of scientific calculations from solving linear equations to generating binomial random deviates</p>Relevansi: 35%<br></div><br><div class=\'book-recommendation\'><strong>3. C++ for Dummies (3rd ed)</strong><br>Penulis: By Davis, Stephen R.<br>Kategori: Computers , Programming Languages , C++<br>Tahun: 1998<br><p><em>Deskripsi:</em> Explains how to develop applications, write effective programs with Inheritance, explore C++\'s optional features, and create object-oriented programming</p>Relevansi: 34%<br></div><br><div class=\'book-recommendation\'><strong>4. Wiley Learns to Spell (Hello Reader!, Level 1)</strong><br>Penulis: By Lewin, Betsy<br>Kategori: Juvenile Fiction , Concepts , Words<br>Tahun: 1998<br><p><em>Deskripsi:</em> Wiley, a mischievous little monster, refuses to give up as he struggles, with the help of a friend, to learn how to spell &quot;cat.&quot; Original.</p>Relevansi: 33%<br></div><br><div class=\'book-recommendation\'><strong>5. Problem Solving and Program Design in C (3rd Edition)</strong><br>Penulis: By Hanly, Jeri R. and Koffman, Elliot B.<br>Kategori: Computers , Programming Languages , C<br>Tahun: 2002<br><p><em>Deskripsi:</em> This textbook introduces the basics of writing computer programs with the C language.  The book describes syntax, notation, functions, loop statements, arrays, strings, recursion, pointers, and lin...</p>Relevansi: 32%<br></div><br><div class=\'book-recommendation\'><strong>6. Portable UNIX</strong><br>Penulis: By Topham, Douglas W.<br>Kategori: Computers , Operating Systems , UNIX<br>Tahun: 1993<br><p><em>Deskripsi:</em> Using a dictionary-style format, organized by task rather than command, this is an accessible and easy-to-use reference on AT&amp;T UNIX System V, Release 4. Utilities, text and number processing, file...</p>Relevansi: 31%<br></div><br><div class=\'book-recommendation\'><strong>7. CliffsNotes on Defoe\'s Moll Flanders</strong><br>Penulis: By Arnez, Nancy Levi (EDT)<br>Kategori: Study Aids , Study Guides<br>Tahun: 1969<br><p><em>Deskripsi:</em> This is a topsy-turvy story of a woman born in prison who led a life of crime and wantonness. Later in life she straightens herself out, grows rich, and lives an honest and penitent life.</p>Relevansi: 30%<br></div><br><div class=\'book-recommendation\'><strong>8. Programming Perl</strong><br>Penulis: By Potter, Stephen, Schwartz, Randal L., Christiansen, Tom, and Wall, Larry (EDT)<br>Kategori: Computers , Operating Systems , UNIX<br>Tahun: 1996<br><p><em>Deskripsi:</em> Programming Perl, 2nd Edition is the authoritative guide to Perl  version 5, the scripting utility that has established itself as the  programming tool of choice for the World Wide Web, UNIX system...</p>Relevansi: 30%<br></div><br><div class=\'book-recommendation\'><strong>9. Sams Teach Yourself Beginning Programming in 24 Hours</strong><br>Penulis: By Perry, Greg M.<br>Kategori: Computers , Programming , General<br>Tahun: 1998<br><p><em>Deskripsi:</em> An easy-to-follow tutorial in the fundamentals of computer programming offers instruction and self-assessment exercises in QBasic, C++, Visual Basic, Internet programming languages, macro and batch...</p>Relevansi: 30%<br></div><br><div class=\'book-recommendation\'><strong>10. Clifford\'s First Autumn (Clifford 8x8)</strong><br>Penulis: By Bridwell, Norman<br>Kategori: Juvenile Fiction , Social Themes , New Experience<br>Tahun: 1997<br><p><em>Deskripsi:</em> Clifford the Small Red Puppy leaps into autumn when he experiences the changing of the seasons for the first time, and he joyfully sniffs the falling leaves and enjoys the wonderful colors. Original.</p>Relevansi: 28%<br></div><br></details><p>Apakah Anda ingin rekomendasi buku lainnya? Silakan ketik pertanyaan atau topik yang Anda minati.</p>', '2025-05-15 17:15:21'),
(398, 8, 'boleh', '<p><strong>?? Pencarian Buku:</strong><br>Silakan masukkan kembali judul atau deskripsi buku Anda.</p>', '2025-05-15 17:15:59'),
(399, 8, 'history of niggers', '<strong>Buku yang paling relevan untuk Anda:</strong><br><br><details><summary>???? Lihat rekomendasi tambahan</summary><br><div class=\'book-recommendation\'><strong>1. Nat Turner (Black Americans of Achievement)</strong><br>Penulis: By Bisson, Terry and Huggins, Nathan Irvin (EDT)<br>Kategori: Young Adult Nonfiction , Biography &amp; Autobiography , Cultural Heritage<br>Tahun: 1989<br><p><em>Deskripsi:</em> A biography of the slave and preacher who, believing that God wanted him to free the slaves, led a major revolt in 1831</p>Relevansi: 37%<br></div><br><div class=\'book-recommendation\'><strong>2. Abraham Lincoln and the Road to Emancipation</strong><br>Penulis: By Klingaman, William K.<br>Kategori: Biography &amp; Autobiography , Presidents &amp; Heads of State<br>Tahun: 2001<br><p><em>Deskripsi:</em> A new history of the Civil War assesses the impact of the Emancipation Proclamation and Lincoln\'s sometimes ambivalent abolitionist sympathies on the eventual end of slavery. By the author of 1919:...</p>Relevansi: 37%<br></div><br><div class=\'book-recommendation\'><strong>3. Nationalism and Development in Africa: Selected Essays</strong><br>Penulis: By Coleman, James Smoot and Sklar, Richard L. (EDT)<br>Kategori: History , Africa , General<br>Tahun: 1994<br><p><em>Deskripsi:</em> James Smoot Coleman was the leading theorist of his time in African political studies. His work fused liberal-democratic idealism and scientific realism. These essays represent the evolution of his...</p>Relevansi: 37%<br></div><br><div class=\'book-recommendation\'><strong>4. Hugo Black: A Biography</strong><br>Penulis: By Newman, Roger K.<br>Kategori: Biography &amp; Autobiography , Lawyers &amp; Judges<br>Tahun: 1997<br><p><em>Deskripsi:</em> The extraordinary story of a man who bestrode his era like a colossus, Hugo Black is the first and only comprehensive biography of the Supreme Court Justice of thirty four years, (1886-1971). Once ...</p>Relevansi: 34%<br></div><br><div class=\'book-recommendation\'><strong>5. Africans: The History of a Continent (African Studies, Series Number 85)</strong><br>Penulis: By Iliffe, John<br>Kategori: History , Africa , General<br>Tahun: 1995<br><p><em>Deskripsi:</em> This history of Africa from the origins of mankind to the South African general election of 1994 refocuses African history on the peopling of an environmentally hostile continent.  The social, econ...</p>Relevansi: 33%<br></div><br><div class=\'book-recommendation\'><strong>6. Orphan in History, An</strong><br>Penulis: By Cowan, Paul<br>Kategori: Religion , Judaism , General<br>Tahun: 1989<br><p><em>Deskripsi:</em> A Jewish writer portrays his personal struggle to rediscover the religious and cultural heritage that his family lost through their assimilation into American society</p>Relevansi: 33%<br></div><br><div class=\'book-recommendation\'><strong>7. Harriet Tubman: Antislavery Activist (Black Americans of Achievement)</strong><br>Penulis: By Huggins, Nathan Irvin and Taylor, Marian<br>Kategori: Young Adult Nonfiction , Biography &amp; Autobiography , Cultural Heritage<br>Tahun: 1990<br><p><em>Deskripsi:</em> Describes the life of the energetic abolitionist, including her origins as a slave in Maryland, her role as a &quot;conductor&quot; for the Underground Railroad, her service to the Union during the Civil War...</p>Relevansi: 33%<br></div><br><div class=\'book-recommendation\'><strong>8. The African Genius: An Introduction to African Social and Cultural History</strong><br>Penulis: By Davidson, Basil<br>Kategori: History , Africa , General<br>Tahun: 1989<br><p><em>Deskripsi:</em> Studies the social, religious, and political structures of ancient African societies and the way in which social change and modern attitudes have been incorporated into these traditions</p>Relevansi: 32%<br></div><br><div class=\'book-recommendation\'><strong>9. An Orphan in History: Retrieving a Jewish Legacy</strong><br>Penulis: By Cowan, Paul<br>Kategori: Religion , Judaism , General<br>Tahun: 1982<br><p><em>Deskripsi:</em> A Jewish writer portrays his personal struggle to rediscover the religious and cultural heritage that his family lost through their assimilation into American society</p>Relevansi: 32%<br></div><br><div class=\'book-recommendation\'><strong>10. Africa since 1800</strong><br>Penulis: By Atmore, Anthony and Oliver, Roland<br>Kategori: History , Africa , General<br>Tahun: 1994<br><p><em>Deskripsi:</em> This general history of modern Africa has been revised and updated to take full account of the fresh perspectives on African history brought about by the end of the Cold War. It is also concerned w...</p>Relevansi: 32%<br></div><br></details><p>Apakah Anda ingin rekomendasi buku lainnya? Silakan ketik pertanyaan atau topik yang Anda minati.</p>', '2025-05-15 17:16:06'),
(400, 8, 'ya', '<p><strong>?? Pencarian Buku:</strong><br>Silakan masukkan kembali judul atau deskripsi buku Anda.</p>', '2025-05-15 17:16:25'),
(401, 8, 'history of europe', '<strong>Buku yang paling relevan untuk Anda:</strong><br><br><div class=\'book-recommendation\'><strong>1. Over Europe</strong><br>Penulis: By Morris, Jan<br>Kategori: Travel , General<br>Tahun: 1992<br><p><em>Deskripsi:</em> Aerial photographs depict the cities and towns, countryside, and historic sites of the British Isles, Scandinavia, and the continent of Europe west of the former Soviet Union</p>Relevansi: 58%<br></div><br><div class=\'book-recommendation\'><strong>2. The European Union: A Very Short Introduction (Very Short Introductions)</strong><br>Penulis: By Pinder, John<br>Kategori: Political Science , Public Policy , Economic Policy<br>Tahun: 2001<br><p><em>Deskripsi:</em> Over the past few decades, the European Union has seen many great changes. Negotiations for the accession of six new states have begun, and membership, which already covers almost all of Western Eu...</p>Relevansi: 52%<br></div><br><div class=\'book-recommendation\'><strong>3. The Prospect Before Her: A History of Women in Western Europe, 1500-1800</strong><br>Penulis: By Hufton, Olwen H.<br>Kategori: Social Science , Women\'s Studies<br>Tahun: 1996<br><p><em>Deskripsi:</em> The first in a two-volume history of women in Western Europe ranges from the sixteenth century to the beginning of the modern age, documenting women\'s childhoods, the role of marriage, male-female ...</p>Relevansi: 49%<br></div><br><div class=\'book-recommendation\'><strong>4. Thunder at Twilight: Vienna, 1913-1914</strong><br>Penulis: By Morton, Frederic<br>Kategori: History , Europe , Italy<br>Tahun: 1989<br><p><em>Deskripsi:</em> A recreation of Vienna on the eve of WWI. By the author of  A Nervous splendor  and  The Rothschilds  (both nominated for the National Book Award). Annotation copyright Book News, Inc. Portland, Or.</p>Relevansi: 49%<br></div><br><div class=\'book-recommendation\'><strong>5. The Celts: Conquerors of Ancient Europe (Discoveries (Abrams))</strong><br>Penulis: By Eluere, Christiane<br>Kategori: History , Ancient , General<br>Tahun: 1993<br><p><em>Deskripsi:</em> This little colour illustrated guide introduces the history of  the Celts, from the birth of a warrior aristocracy to their  conflict with Rome. Eluere looks at the Celts\' historical,  artistic and...</p>Relevansi: 49%<br></div><br><div class=\'book-recommendation\'><strong>6. Napoleon and Hitler</strong><br>Penulis: By Seward, Desmond<br>Kategori: History , Europe , Western<br>Tahun: 1989<br><p><em>Deskripsi:</em> Examines the possible similarities between two of the world\'s worst military dictators. The link: Carl von Clausewitz\'s treatise,  On war , which draws extensively on Napoleon\'s battles and which H...</p>Relevansi: 48%<br></div><br><div class=\'book-recommendation\'><strong>7. Twentieth-Century France: Politics and Society in France 1898-1991 (Hodder Arnold Publication)</strong><br>Penulis: By McMillan, James F.<br>Kategori: History , Europe , France<br>Tahun: 1992<br><p><em>Deskripsi:</em> Originally published as &quot;Dreyfus to de Gaulle&quot;, this authoritative account has now been revised and extended and is the best up-to-date introduction to the history of modern France.</p>Relevansi: 48%<br></div><br><div class=\'book-recommendation\'><strong>8. Leap into Darkness: Seven Years on the Run in Wartime Europe</strong><br>Penulis: By Olesker, Michael and Bretholz, Leo<br>Kategori: History , Holocaust<br>Tahun: 1998<br><p><em>Deskripsi:</em> The author recounts his experiences in Austria and France during the Holocaust</p>Relevansi: 47%<br></div><br><div class=\'book-recommendation\'><strong>9. Vikings!</strong><br>Penulis: By Magnusson, Magnus<br>Kategori: History , Europe , Medieval<br>Tahun: 1980<br><p><em>Deskripsi:</em> Utilizes recent archeological discoveries in the U.S. and Europe to explore Viking culture, portraying Vikings as traders, architects, town dwellers, and craftsmen</p>Relevansi: 46%<br></div><br><div class=\'book-recommendation\'><strong>10. A Concise History of England from Stonehenge to the Microchip (Illustrated National Histories)</strong><br>Penulis: By Halliday, F. E.<br>Kategori: History , Europe , Great Britain<br>Tahun: 1980<br><p><em>Deskripsi:</em> This brief, overall look at English history links the historical periods through the dominant theme of the conflict between order and liberty</p>Relevansi: 46%<br></div><br><p>Apakah Anda ingin rekomendasi buku lainnya? Silakan ketik pertanyaan atau topik yang Anda minati.</p>', '2025-05-15 17:16:32'),
(402, 8, 'enggak kok bang', '<p><strong>? Permintaan dibatalkan, Sir.</strong> Silakan sampaikan jika ada yang lain.</p>', '2025-05-15 17:17:16'),
(403, 8, 'terimakasih aku pergi', '<p><strong>Sampai jumpa, Sir!</strong> Semoga harimu penuh pengetahuan dan inspirasi. ??</p>', '2025-05-15 17:17:31'),
(404, 10, 'Assalamualaikum, saya ingin pake!', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-26 04:44:35'),
(405, 10, 'hello', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-26 04:45:28'),
(406, 10, 'p', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-26 04:48:33'),
(407, 10, 'kapal kargo banyu wangi', '<p>Maaf, Sir, saya belum memahami maksud Anda. Bisa dijelaskan lagi? ??</p>', '2025-05-26 04:49:17'),
(408, 10, 'hello', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-26 04:50:59'),
(409, 10, 'aku pergi', '<p><strong>Sampai jumpa, Sir!</strong> Semoga harimu penuh pengetahuan dan inspirasi. ??</p>', '2025-05-26 04:58:41'),
(410, 10, 'carikan buku bs', '<p><strong>?? Pencarian Buku:</strong><br>Ketik judul, pengarang, atau kata kunci buku yang Anda cari.<br><em>Contoh:</em> cari buku \"Pemrograman Python untuk Pemula\"</p>', '2025-05-26 05:12:33'),
(411, 10, 'Jawa sex', '<strong>Buku yang paling relevan untuk Anda:</strong><br><br><details><summary>???? Lihat rekomendasi tambahan</summary><br><div class=\'book-recommendation\'><strong>1. The New Good Vibrations Guide to Sex: Tips and Techniques from America\'s Favorite Sex Toy Store, 2nd Edition</strong><br>Penulis: By Winks, Cathy and Semans, Anne<br>Kategori: Self-help , Sexual Instruction<br>Tahun: 1997<br><p><em>Deskripsi:</em> Covers sexual self-image, sexual anatomy, communication, erotic  massage, oral sex, intercourse, sexual devices, fantasies, sexual behavior, and safe sex</p>Relevansi: 38%<br></div><br><div class=\'book-recommendation\'><strong>2. The Good Girl\'s Guide to Bad Girl Sex: An Indispensable Resource to Pleasure and Seduction</strong><br>Penulis: By Keesling, Barbara<br>Kategori: Psychology , Interpersonal Relations<br>Tahun: 2001<br><p><em>Deskripsi:</em> Seduction and unabashed sexual pleasure are the goals of this guide to turning good girls bad, with advice on looking the part,  touching, teasing, orgasm, toys, and much more.</p>Relevansi: 38%<br></div><br><div class=\'book-recommendation\'><strong>3. Night Games</strong><br>Penulis: By Bangs, Nina<br>Kategori: Fiction , Romance , Time Travel<br>Tahun: 2002<br><p><em>Deskripsi:</em> A sex competitor from the future, Brian Byrne, skilled in the art of lust, seduction, and pleasure, meets his match in American tourist, author, and divorcTe Ally O\'Neill who teaches him that sex i...</p>Relevansi: 36%<br></div><br><div class=\'book-recommendation\'><strong>4. Tease</strong><br>Penulis: By Forster, Suzanne<br>Kategori: Fiction , Romance , Erotica<br>Tahun: 2006<br><p><em>Deskripsi:</em> Trying to impress her new employer, Manhattan advertising executive Tess Wakefield delves into the city\'s underground S&amp;M culture to find a model for her edgy new campaign and encounters a work col...</p>Relevansi: 34%<br></div><br><div class=\'book-recommendation\'><strong>5. Satisfaction: The Art of the Female Orgasm</strong><br>Penulis: By Cattrall, Kim, Levinson, Mark, and Drury, Fritz<br>Kategori: Self-help , Sexual Instruction<br>Tahun: 2002<br><p><em>Deskripsi:</em> Kim Cattrall, &quot;Sex and the City&quot;\'s Samantha, slides between the sheets and shares her secrets on reaching the heights of pleasure. She teams up with her husband in this how-to-sex book, based on vi...</p>Relevansi: 34%<br></div><br><div class=\'book-recommendation\'><strong>6. Kama Sutra</strong><br>Penulis: By Hooper, Anne<br>Kategori: Self-help , Sexual Instruction<br>Tahun: 2000<br><p><em>Deskripsi:</em> Get ready to enjoy the most erotic sexual experiences of your life with this gorgeous visual guide. From the author of the Great Sex Guide, celebrated sex therapist Anne Hooper joins contemporary t...</p>Relevansi: 33%<br></div><br><div class=\'book-recommendation\'><strong>7. Battle of the Sexes (Spinner Books)</strong><br>Penulis: By Conley, Erin (EDT)<br>Kategori: Games , General<br>Tahun: 1998<br><p><em>Deskripsi:</em> This fun, fast-paced game pits men against women in the ultimate battle of the sexes. Players can spin the spinner, answer gender-based questions, settle the score and find out once and for all whi...</p>Relevansi: 32%<br></div><br><div class=\'book-recommendation\'><strong>8. A.D.D. &amp; Romance: Finding Fulfillment in Love, Sex, &amp; Relationships</strong><br>Penulis: By Halverstadt, Jonathan Scott<br>Kategori: Family &amp; Relationships , Love &amp; Romance<br>Tahun: 1998<br><p><em>Deskripsi:</em> Discusses the neurobiological origins of Attention Deficit Disorder, and how it can affect sexual intimacy and desire</p>Relevansi: 32%<br></div><br><div class=\'book-recommendation\'><strong>9. Intended for Pleasure: New Approaches to Sexual Intimacy in Christian Marriage</strong><br>Penulis: By Wheat, Ed and Wheat, Gaye<br>Kategori: Religion , Ethics<br>Tahun: 1981<br><p><em>Deskripsi:</em> Combines scriptural teachings on love and marriage with the latest medical information on human sexuality to lead Christian couples to a full awareness of the pleasures that can be found in sexual ...</p>Relevansi: 32%<br></div><br><div class=\'book-recommendation\'><strong>10. Lust</strong><br>Penulis: By Pyle, Howard and Wasserman, Robin<br>Kategori: Young Adult Fiction , Social Themes , Friendship<br>Tahun: 2005<br><p><em>Deskripsi:</em> Alpha girl Harper is used to getting what she wants,  and that means Adam,  Beth\'s all-American boytoy.  Blond, boring Beth, who Kane,  the charming playah, secretly wants too.  Miranda thinks Kane...</p>Relevansi: 32%<br></div><br></details><p>Apakah Anda ingin rekomendasi buku lainnya? Silakan ketik pertanyaan atau topik yang Anda minati.</p>', '2025-05-26 05:13:02'),
(412, 10, 'iya', '<p><strong>?? Pencarian Buku:</strong><br>Silakan masukkan kembali judul atau deskripsi buku Anda.</p>', '2025-05-26 05:13:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_detail`
--

CREATE TABLE `chat_detail` (
  `id` int(255) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `chat_id` int(255) DEFAULT NULL,
  `intent` varchar(255) DEFAULT NULL,
  `confident_score` float DEFAULT NULL,
  `energy` float DEFAULT NULL,
  `ood` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `chat_detail`
--

INSERT INTO `chat_detail` (`id`, `user_id`, `chat_id`, `intent`, `confident_score`, `energy`, `ood`, `timestamp`) VALUES
(1, 10, 404, 'greeting', 0.959325, -5.13138, 0, '2025-05-26 04:44:35'),
(2, 10, 405, 'greeting', 0.952335, -5.08748, 0, '2025-05-26 04:45:28'),
(3, 10, 406, 'greeting', 0.963104, -5.0404, 0, '2025-05-26 04:48:33'),
(4, 10, 407, 'unknown', 0.449495, -3.43633, 1, '2025-05-26 04:49:17'),
(5, 10, 408, 'greeting', 0.952335, -5.08748, 0, '2025-05-26 04:50:59'),
(6, 10, 409, 'goodbye', 0.964846, -4.99582, 0, '2025-05-26 04:58:41'),
(7, 10, 410, 'cari_buku', 0.981202, -5.49353, 0, '2025-05-26 05:12:33'),
(8, 10, 412, 'confirm', 0.976218, -5.4991, 0, '2025-05-26 05:13:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_probabilities`
--

CREATE TABLE `class_probabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prediction_id` int(11) DEFAULT NULL,
  `intent_class` varchar(100) NOT NULL,
  `probability` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `class_probabilities`
--

INSERT INTO `class_probabilities` (`id`, `prediction_id`, `intent_class`, `probability`) VALUES
(1, 6, 'cara_pinjam', 0.00263718),
(2, 6, 'cari_buku', 0.00285828),
(3, 6, 'confirm', 0.00175144),
(4, 6, 'denied', 0.00786955),
(5, 6, 'fasilitas', 0.00182712),
(6, 6, 'goodbye', 0.964846),
(7, 6, 'greeting', 0.00979463),
(8, 6, 'jam_layanan', 0.00580042),
(9, 6, 'keanggotaan', 0.00261484),
(10, 7, 'cara_pinjam', 0.00275824),
(11, 7, 'cari_buku', 0.981202),
(12, 7, 'confirm', 0.00144101),
(13, 7, 'denied', 0.00241993),
(14, 7, 'fasilitas', 0.00510462),
(15, 7, 'goodbye', 0.00223828),
(16, 7, 'greeting', 0.00174749),
(17, 7, 'jam_layanan', 0.00209844),
(18, 7, 'keanggotaan', 0.000989511),
(19, 8, 'cara_pinjam', 0.00150814),
(20, 8, 'cari_buku', 0.00131183),
(21, 8, 'confirm', 0.976218),
(22, 8, 'denied', 0.0132074),
(23, 8, 'fasilitas', 0.00172882),
(24, 8, 'goodbye', 0.0018847),
(25, 8, 'greeting', 0.00188672),
(26, 8, 'jam_layanan', 0.000963481),
(27, 8, 'keanggotaan', 0.00129123);

-- --------------------------------------------------------

--
-- Struktur dari tabel `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `intent` varchar(100) NOT NULL,
  `response` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `responses`
--

INSERT INTO `responses` (`id`, `intent`, `response`, `updated_at`) VALUES
(1, 'greeting', '<p><strong>Halo, Sir!</strong> ?? Selamat datang di Perpustakaan Digital. Ada yang bisa saya bantu hari ini? ??</p>', '2025-05-26 08:45:32'),
(2, 'goodbye', '<p><strong>Sampai jumpa, Sir!</strong> Semoga harimu penuh pengetahuan dan inspirasi. ??</p>', '2025-05-26 08:46:42'),
(3, 'jam_layanan', '<p><strong>?? Jam Layanan Perpustakaan:</strong></p><ul><li><strong>Senin - Kamis</strong>: 08.00 - 15.30</li><li><strong>Jumat & Sabtu</strong>: 08.00 - 18.00</li><li><strong>Minggu</strong>: 08.00 - 12.00</li></ul>', '2025-05-26 09:12:46'),
(4, 'keanggotaan', '<p><strong>?? Informasi Keanggotaan:</strong></p><ol><li>Mengisi formulir pendaftaran online/di tempat</li><li>Melampirkan fotokopi KTP/KTM</li><li>Menyerahkan 2 lembar pas foto 3x4</li></ol><p>Setelah data diverifikasi, kartu anggota akan dikirimkan via email/diambil langsung. Kartu ini memungkinkan Anda meminjam buku fisik dan mengakses koleksi digital.</p><p>Bagi mahasiswa dari luar STMIK BINA PATRIA MAGELANG diperbolehkan memanfaatkan fasilitas perpustakaan, kecuali peminjaman buku. Syarat untuk memanfaatkan fasilitas adalah dengan menunjukkan kartu mahasiswa yang masih berlaku.</p>', '2025-05-26 09:12:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`) VALUES
(5, 'nigga', 'nigga@gmail.com', '12345678'),
(6, 'Rio Kurniawan', 'RioKURRR@gmail.com', '12345678'),
(7, 'Jahseh Dwayne Ricardo Onfroy ', 'jahseh@onfroy.com', '123456789'),
(8, 'Novi', 'perpusbinapatria@gmail.com', '12345678'),
(9, 'Mursyid Mursalin', 'mursyid@musical.com', '12345678'),
(10, 'Arief Rizal Bayhaqi ', 'elisewasoson341@gmail.com', '12345678');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indeks untuk tabel `chat_detail`
--
ALTER TABLE `chat_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cfhyj` (`chat_id`),
  ADD KEY `niage` (`user_id`);

--
-- Indeks untuk tabel `class_probabilities`
--
ALTER TABLE `class_probabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=413;

--
-- AUTO_INCREMENT untuk tabel `chat_detail`
--
ALTER TABLE `chat_detail`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `class_probabilities`
--
ALTER TABLE `class_probabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `fssnggd` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chat_detail`
--
ALTER TABLE `chat_detail`
  ADD CONSTRAINT `cfhyj` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `niage` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
