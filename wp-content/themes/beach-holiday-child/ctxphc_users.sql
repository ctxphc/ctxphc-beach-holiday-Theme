-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2012 at 10:26 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ctxphc_wp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ctxphc_users`
--

CREATE TABLE IF NOT EXISTS `ctxphc_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1649 ;

--
-- Dumping data for table `ctxphc_users`
--

INSERT INTO `ctxphc_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES
(1, 'admin', '$P$BvWRrvRLPFNgDPROKvOsHtYjKkICLa.', 'admin', 'ctxphc@ctxphc.com', '', '2012-04-12 03:07:50', '', 0, 'admin'),
(5, 'president', '$P$BzOBVHikCo/jW171w1Ypo4T2339Ctb1', 'president', 'president@ctxphc.com', '', '2012-08-17 19:55:13', '', 0, 'president'),
(6, 'vice-president', '$P$BVT9kMlJw9arfqN7qxBd44DBaKtpbS/', 'vice-president', 'vice-president@ctxphc.com', '', '2012-08-17 19:57:19', '', 0, 'Vice-President CTXHPC'),
(7, 'membership', '$P$BMUubPom/3NMm/rmofQ1dBGNNRjybw0', 'membership', 'membership@ctxphc.com', '', '2012-08-17 19:58:09', '', 0, 'membership'),
(8, 'philanthropy', '$P$BkKmdfcEaYVyLBfiaAgdy5E.OKJgNL1', 'philanthropy', 'philanthropy@ctxphc.com', '', '2012-08-17 19:59:08', '', 0, 'philanthropy'),
(9, 'treasurer', '$P$B0tlFohrGizdAupfeqCwXlecQdF/Eh/', 'treasurer', 'treasurer@ctxphc.com', '', '2012-08-17 20:00:24', '', 0, 'treasurer'),
(10, 'promotions', '$P$BElVfNanpEGiz8YPGIXCcH81AVjMX.1', 'promotions', 'promotions@ctxphc.com', '', '2012-08-17 20:01:22', '', 0, 'promotions'),
(11, 'newsletter', '$P$BHQZJcwfxz1dhroG1M6zv4jqgPbO991', 'newsletter', 'newsletter@ctxphc.com', '', '2012-08-17 20:06:37', '', 0, 'newsletter'),
(1563, 'mborgstra', '$P$BZ9So3/WJxQTlW9RJioB8FT8Sd8VTS.', 'Matt Borgstrand', 'borgstrand@austin.rr.com', '', '2001-04-15 01:00:00', '', 0, 'Matt'),
(1564, 'dhall', '$P$BKfOv5zBgjZGhHhpY4VHT7v8akw/Df.', 'Doug Hall', 'cabanadaddy@gmail.com', '', '2002-04-03 02:00:00', '', 0, 'Doug'),
(1565, 'bjohnson', '$P$B4Aczq7Y8WtSDTuFZlUaW1nuOsnBwr/', 'Bruce Johnson', 'bajohnson@austin.rr.com', '', '2002-02-08 01:00:00', '', 0, 'Bruce'),
(1566, 'lquisenbe', '$P$BucYiEcvm2SiG/W.jOzRiTgM4jrVzO.', 'Larry Quisenberry', 'quisntx@gmail.com', '', '2002-04-03 02:00:00', '', 0, 'Larry'),
(1567, 'krohlfs', '$P$BbFZT3JgR./JldyBr0lGF7DMesSo8K/', 'Karen Rohlfs', 'tropicaldiva@austin.rr.com', '', '1998-07-18 01:00:00', '', 0, 'Karen'),
(1568, 'fguerrero', '$P$BJjWjg4grhQztup8nJ.CK0y/VAaY.I1', 'Fred Guerrero', 'islnddrmr1@aol.com', '', '2002-11-05 01:00:00', '', 0, 'Fred'),
(1569, 'dstephani', '$P$BCVUn9cVhWaiNHFJZqSJLXv2/kYzzW/', 'Danny Stephen', '', '', '2003-06-14 01:00:00', '', 0, 'Danny'),
(1570, 'jwatson', '$P$BWRGtHkCZVuM6NsXwbkVplw1qQ82sS.', 'James Watson', 'jwatson@rgv.rr.com', '', '2003-03-29 02:00:00', '', 0, 'James'),
(1571, 'rbarr', '$P$BM6a8r08XZE.10Eyv8hwr38/znLHui/', 'Ron Barr', 'dr_bud_weiser@yahoo.com', '', '1997-09-15 01:00:00', '', 0, 'Ron'),
(1572, 'jneilson', '$P$BO4koNX.sw4pvv44RoFjvXe0nJC/po1', 'Jim Neilson', 'mmneilson@austin.rr.com', '', '1998-08-07 01:00:00', '', 0, 'Jim'),
(1573, 'kaptkaos', '$P$B1dxU6SnvrMZdhQdnVmQdgN2B/Gw.H.', 'Ken Kilgore', 'kaptkaos@gmail.com', '', '1998-11-12 01:00:00', '', 0, 'Ken'),
(1574, 'parrothea', '$P$BNXPOyxR3r4VOo5w.LOMcZvVM/sbLy1', 'Jim Fritz', 'parrothead1966@aol.com', '', '2004-06-08 01:00:00', '', 0, 'Jim'),
(1575, 'mfalgoust', '$P$BPYIlCwOIZuSrNwwwydI7Jd8Gclqfr1', 'Michael Falgoust', 'mfalgoust@austin.rr.com', '', '2004-11-11 01:00:00', '', 0, 'Michael'),
(1576, 'kimberlit', '$P$BtRcLbfIo3L3Vh4tOfQeHjx0XHT5mo.', 'Kimberly Paternoster', 'kim@ctxphc.com', '', '2003-05-09 01:00:00', '', 0, 'Kimberly'),
(1577, 'squarmby', '$P$BFSkh3aM6CnQTBWQUQpyPAZs9Lhjci.', 'Scott Quarmby', 'quarmby@juno.com', '', '2005-05-16 01:00:00', '', 0, 'Scott'),
(1578, 'remccrea', '$P$Bd3pmDU35npo.OcmHw7tDHzVIm2LB31', 'Randy McCrea', 'remccrea@suddenlink.net', '', '2005-06-12 01:00:00', '', 0, 'Randy'),
(1579, 'dbarnes', '$P$BDlKsikn/E0teRs0W/bbW3jm3PEsDN/', 'Denise Barnes', 'barnes.denise55@yahoo.com', '', '2005-08-25 01:00:00', '', 0, 'Denise'),
(1580, 'sbodi', '$P$Bz1SZEcdykP1C1jgFtx9ZVWlkwJ9zV.', 'Stephen Bodi', 'ssbodi@austin.rr.com', '', '2005-08-27 01:00:00', '', 0, 'Stephen'),
(1581, 'c_springs', '$P$Bt3r4L12We05CcSpBX2vRD4FZLP2eW/', 'Chuck Springs', 'chuck.springs@gmail.com', '', '2005-11-12 01:00:00', '', 0, 'Chuck'),
(1582, 'kruzicka', '$P$BE381gI5eNci44eviiIheaeNvxWkKM1', 'Katy Ruzicka', 'sluggo_ut@yahoo.com', '', '2006-02-12 01:00:00', '', 0, 'Katy'),
(1583, 'jdueease', '$P$Bv25LxV3BNesZyn1D6y97dID5ssn5t/', 'Jane Dueease', 'janed2@usa.net', '', '2006-05-25 01:00:00', '', 0, 'Jane'),
(1584, 'hknopp', '$P$Bz2YPYRmnOeivASxDA0EAy.AuBwdHa1', 'Helen Knopp', 'Helen.Knopp@gmail.com', '', '2006-06-14 01:00:00', '', 0, 'Helen'),
(1585, 'pmenzies', '$P$B6Zbgpa7cjEB2CeUoJteoqemQITQNW1', 'Pam Mathews (Menzies)', 'rajah1fan@yahoo.com', '', '1999-01-01 01:00:00', '', 0, 'Pam'),
(1586, 'jweltz', '$P$BPnn7Jd8VSlU7RpJV5TQeFBlF.AVKW1', 'Jennifer Weltz', 'hulagirl78749@gmail.com', '', '2006-07-03 01:00:00', '', 0, 'Jennifer'),
(1587, 'rogwood', '$P$Bk8RdgjA0JK1KftBc58GAl8yyOGXkh1', 'Roger Wood', 'roger.wood@freescale.com', '', '2006-07-18 01:00:00', '', 0, 'Roger'),
(1588, 'cwatson', '$P$BrN4VS./.MbFZ1PFbif5O5gVKGEBHn.', 'Carmen Watson', 'carmenwatson@ge.com', '', '2006-11-27 01:00:00', '', 0, 'Carmen'),
(1589, 'tweber', '$P$Bb9Hu.xoBwV29c1cydNAwT5nEL1hvZ.', 'Tami Weber', 'tdweber@yahoo.com', '', '2007-01-04 01:00:00', '', 0, 'Tami'),
(1590, 'pkaisner', '$P$BhVxVd.Dqhnrn/1TjZQ5Q5Yle9BUMs.', 'Paula Kaisner', 'paula@kaisner.org', '', '2004-01-01 01:00:00', '', 0, 'Paula'),
(1591, 'kburns', '$P$BMhkSsTXw0GjaGvCzEH8bSgRCsFtTX1', 'Kristi Burns', 'kburns66@yahoo.com', '', '2007-02-17 01:00:00', '', 0, 'Kristi'),
(1592, 'shansen', '$P$BEn1BuN5mtFqENCZJyWOK02P5R4OWr/', 'Steve Hansen', 'shansendds@gmail.com', '', '2008-01-16 01:00:00', '', 0, 'Steve'),
(1593, 'bderricks', '$P$Bdq9L/bRDSf2For/te.xiiRPomENLX1', 'Bill Derrickson', 'patderrickson@aol.com', '', '2008-02-07 01:00:00', '', 0, 'Bill'),
(1594, 'jadkins', '$P$BX4fFr4Tx7okmISXpdLQcRb5g3bNrL0', 'Jaime Adkins', 'jaime.adkins@yahoo.com', '', '2008-03-13 01:00:00', '', 0, 'Jaime'),
(1595, 'fstephani', '$P$BLat2MZ6jmJgXzqVUNmb1v5z8CLtL1.', 'Stephanie Ferguson', 'stephanie.ferguson@leanderisd.org', '', '2008-04-12 01:00:00', '', 0, 'Stephanie'),
(1596, 'ccamp1', '$P$BJB5MFLfD2AY13vSzl/bGVyOLv8j7P1', 'Carolyn Camp', 'ccamp@austin.rr.com', '', '2008-10-14 01:00:00', '', 0, 'Carolyn'),
(1597, 'cmondrik', '$P$B48RelHhR6qXFcEy6HvwvXQpFtE.2M0', 'Christi Mondrik', 'cmondrik@austin.rr.com', '', '2008-10-14 01:00:00', '', 0, 'Christi'),
(1598, 'tjennings', '$P$Bw1L/p25h.tuaEe5xPAGFU7x.TYd48.', 'Tom Jennings', 'ironwoodmanor@gmail.com', '', '2009-02-16 01:00:00', '', 0, 'Tom'),
(1599, 'sweet4now', '$P$BnYPZLy4cZP4YwNK9hasz9NDma1IrO1', 'Connie Gray', 'clg2@sbcglobal.net', '', '2008-12-19 01:00:00', '', 0, 'Connie'),
(1600, 'jmarince', '$P$BJuVW9fEQ9WGUgUdBiNkqt3zNu3QlE1', 'Jody Marince', 'jojim83@aol.com', '', '2009-01-14 01:00:00', '', 0, 'Jody'),
(1601, 'kmalcom', '$P$Bngive3EIoHoGLOlsbBX1hO1hylGzX.', 'Kathleen Malcom', '', '', '2009-01-14 01:00:00', '', 0, 'Kathleen'),
(1602, 'tclements', '$P$BGjYfwqRN3CCdcwx/1h9/U/BZRMs2Q1', 'Theodore Clements', 'tclements3@nycap.rr.com', '', '2009-04-01 01:00:00', '', 0, 'Theodore'),
(1603, 'bzuhn', '$P$BpUL7/8VimpV3ev5SO5meao2Peb/bw0', 'Bobby Zuhn', 'ssnakez69@yahoo.com', '', '2009-04-22 01:00:00', '', 0, 'Bobby'),
(1604, 'dnorman', '$P$BMOF9hJj/AUlzXOztleEymKrSirxRs1', 'Dan Norman', 'dnorman76@gmail.com', '', '2009-06-08 01:00:00', '', 0, 'Dan'),
(1605, 'sabbott', '$P$Bi2Vsr2qG2gT6.y.d0CUBcrKdrW9oX.', 'Sheila Abbott', 'sabot11007@aol.comsabbottdel-valle.k12.tx.us', '', '2009-07-30 01:00:00', '', 0, 'Sheila'),
(1606, 'tara c.', '$P$BDgvp8bihG2XcVrcvwyoso7BdDZcpF0', 'Tara Cohen', 'oogiecatt@gmail.com', '', '2009-09-11 01:00:00', '', 0, 'Tara'),
(1607, 'meiras1', '$P$BD50EXPhnvJAZKZOERbC8.V6f74FSY0', 'Michael Eiras', 'MEiras@Earthlink.net', '', '2009-10-02 01:00:00', '', 0, 'Michael'),
(1608, 'bromano', '$P$BmGQjmhXjph26WP1kGvE4dolHqhPSE/', 'Barbara Romano', 'crlferf99@yahoo.com', '', '2009-11-17 01:00:00', '', 0, 'Barbara'),
(1609, 'robin_mil', '$P$B/zf3POnu2mAgWr7INgFD5lyFYFKn5/', 'Robin Millings', 'pauli_girl@austin.rr.com', '', '2002-09-04 01:00:00', '', 0, 'Robin'),
(1610, 'cdurden', '$P$BXoDLagGMKMsbtfFxeZDihKATqxl0R1', 'Chris Durden', 'cadurden@gmail.com', '', '2010-01-29 01:00:00', '', 0, 'Chris'),
(1611, 'debdixon', '$P$BOCX/9eYtI9faz136vBzNHRzij3x7f1', 'Debra Dixon', 'dldixon@austin.rr.com', '', '2006-05-26 01:00:00', '', 0, 'Debra'),
(1612, 'mlane', '$P$BZ4jdMNU8FPX14uBx7bCVI3Elnb3.91', 'Melissa Speerstra (Lane)', 'mmsb11@live.com', '', '2010-01-29 01:00:00', '', 0, 'Melissa'),
(1613, 'snake', '$P$BU7fV5eeZaAOjFl2GIA3hmIZ.1zILA1', 'Dale (Snake) Gerber', 'snake@margaritavillejetski.com', '', '2010-01-29 01:00:00', '', 0, 'Dale (Snake)'),
(1614, 'ejones', '$P$BhcIGxe3ZaCZn3t06rld5bUH1yc4.11', 'Eric Jones', '', '', '2010-01-29 01:00:00', '', 0, 'Eric'),
(1615, 'smitchell', '$P$BIz1mmrcQNiiWAWtM9plgNAxAEq68H1', 'Stephen Mitchell', 'pondpyrate@yahoo.com', '', '2010-02-16 01:00:00', '', 0, 'Stephen'),
(1616, 'mgram', '$P$BOsBYs.6FkSJlrw.hnhg6j04YYwcn1/', 'Margaret Gram', 'speech_mg@yahoo.com', '', '2010-08-11 00:00:00', '', 0, 'Margaret'),
(1617, 'tkopec', '$P$B3ayp/hajgU1OcCoWvbFxF0z9GS/76.', 'Tom Kopec', 'thomas.kopec@oracle.com', '', '2010-11-12 01:00:00', '', 0, 'Tom'),
(1618, 'jeff', '$P$BFSkN4LiZtKH7YJzFvcFp72QpEP2wQ1', 'Jeff Johnson', 'kiteflyer9@yahoo.com', '', '2011-01-02 01:00:00', '', 0, 'Jeff'),
(1619, 'uhogan', '$P$B.bJLiU65OJJ9Xs.mAm5B2C0HbVmT31', 'Lawrence Hogan', 'hogabaretx@yahoo.com', '', '2011-05-26 01:00:00', '', 0, 'Lawrence'),
(1620, 'kwallace1', '$P$BRGYvtWKRmWfNcO8EotN6xYT6vNn8A.', 'Keith Wallace', 'deuceandddoll@juno.com', '', '2011-07-02 01:00:00', '', 0, 'Keith'),
(1621, 'cmendenha', '$P$BJafOzdbTjuA2SlDwQ7ShXBZbUpIvQ/', 'Chris Mendenhall', 'cmendenhall3@yahoo.com', '', '2011-08-15 01:00:00', '', 0, 'Chris'),
(1622, 'tkyles', '$P$Bd865W7bdPMneQgB9ofX6Y/xFarJi6.', 'Tony Kyles', 'tony_kyles@hotmail.com', '', '2011-08-15 01:00:00', '', 0, 'Tony'),
(1623, 'philt', '$P$BmGRsWC0zC5fiFDolLZ8opriYsR1ZV/', 'Paul Hilt', 'usmegles@swbell.net', '', '2011-09-12 01:00:00', '', 0, 'Paul'),
(1624, 'dchettouh', '$P$BTAwIxN8HSUzf5E8UnjvN3nC1VSTvY1', 'Dayna Chettouh', 'daynachettouh@gmail.com', '', '2011-09-15 01:00:00', '', 0, 'Dayna'),
(1625, 'dlee-sue', '$P$B07cZKe725/TZnPojintTA8cFoLuF9/', 'David Lee-Sue', 'whereisdave@yahoo.com', '', '2011-10-18 01:00:00', '', 0, 'David'),
(1626, 'rkennedy', '$P$BgrdjBvu7Ddtqt.twRY2W58A7/gSnX.', 'Ralph Kennedy', 'ralph_m_kennedy@hotmail.com', '', '2011-11-12 01:00:00', '', 0, 'Ralph'),
(1627, 'voodoobro', '$P$Bu/NHiscfpSTwvk2XeqS2fY0vrA7q00', 'Richard Gray', 'rgray656@aol.com', '', '2011-12-15 01:00:00', '', 0, 'Richard'),
(1628, 'wmusser', '$P$BZu8kL/AaORbe7.j/EEWPEYh2y10ur1', 'William Musser', 'bmusser@austin.rr.com', '', '2012-01-13 07:15:00', '', 0, 'William'),
(1629, 'ataylor', '$P$Bz8VWTwolAideitXkIl2Luy5hvzMCZ1', 'Ann Taylor', 'anntaylor.tx@gmail.com', '', '2012-01-30 01:00:00', '', 0, 'Ann'),
(1630, 'slindsey', '$P$BspOjQEtUqNd20vzMpnFU5fzNuGbUN0', 'Sam Lindsey', 'slindsey_tx@yahoo.com', '', '2007-02-17 01:00:00', '', 0, 'Sam'),
(1631, 'jsikes', '$P$BtiXAhEPbqyBvidRIZN8MOf8vuRsLi1', 'Jim Sikes', 'jim.sikes@sbcglobal.net', '', '2012-03-02 01:00:00', '', 0, 'Jim'),
(1632, 'rrender', '$P$BwgBEEF9TZD/i5Pf9WyUA76Wtylk.E1', 'Robert Render', 'robert.render@rentacenter.com', '', '2012-04-11 09:22:00', '', 0, 'Robert'),
(1633, 'clineberr', '$P$Bbh7836E7Tmw3JoqkjRkP3LLlhJBXK1', 'Colin Lineberry', 'colineberry@yahoo.com', '', '2012-04-13 08:50:00', '', 0, 'Colin'),
(1634, 'caraq', '$P$Bi6l6VxsrKKZTfhPQo0lhkJJwftt070', 'Cara Quinn', 'caraquinn333@gmail.com', '', '2010-07-11 01:00:00', '', 0, 'Cara'),
(1635, 'jmhardy', '$P$BfyKGhJByLUPBrvB4Vl4hKsQxxuJ8E0', 'Jason Hardy', 'jhardy73@gmail.com', '', '2012-05-03 01:00:00', '', 0, 'Jason'),
(1636, 'ddwahlgre', '$P$BtCsEIdCPMsAibrLMMJZ9pdF1z2Xjm.', 'David Wahlgren', 'austindsw@hotmail.com', '', '2008-01-16 01:00:00', '', 0, 'David'),
(1637, 'karenturn', '$P$Btl/54xaYwmTWl51jJWIgX41BLp0z61', 'Karen Turner', 'aquaflow1@gmail.com', '', '2008-08-22 01:00:00', '', 0, 'Karen'),
(1638, 'lnzander', '$P$BYZGlG0N1CKA0uuWbrludAT..vPH3x0', 'Lynn Zander', 'namastelz38@hotmail.com', '', '2012-05-29 01:00:00', '', 0, 'Lynn'),
(1639, 'rebmoore', '$P$BGUHiG2tGY3UtSdk/atNiCgenEWRgA0', 'Rebecca Moore', 'bmoorer2@yahoo.com', '', '2010-07-23 01:00:00', '', 0, 'Rebecca'),
(1640, 'thazleton', '$P$BtJKBbN8xIkG2WFPBMcl8iyuXKOGcX1', 'Tommy Hazleton', 'tommy@highdefrealty.com', '', '2012-05-30 01:00:00', '', 0, 'Tommy'),
(1641, 'fschiller', '$P$BDbHap0ylGcSZXtVkzf/uMu1.nJHwV1', 'Frank Schiller', 'fschiller@ohkdlaw.com', '', '2012-05-30 01:00:00', '', 0, 'Frank'),
(1642, 'rcross', '$P$BRRyIwdgvbaLfqz33zB..mdYoWtTrN0', 'Robbi Cross', 'robbicross@austin.rr.com', '', '2012-05-30 01:00:00', '', 0, 'Robbi'),
(1643, 'mstodder', '$P$BToyYQFnM7cbXvJVToQ0ukewF4Viwr0', 'Munson Stodder', 'mstodder@pluckers.net', '', '2012-05-30 01:00:00', '', 0, 'Munson'),
(1644, 'jcarlesto', '$P$BTsCmcDbf2myqNEBY/BiPVQcSBY9Gz1', 'John Carleston', 'utnvols1@yahoo.com', '', '2012-06-22 01:00:00', '', 0, 'John'),
(1645, 'ekilgore', '$P$BhqOVXKNEdQxE8gFZQZa6R6jEcACmh.', 'Erin Kilgore', 'Erin_D_Kilgore@Progressive.com', '', '2000-01-30 01:00:00', '', 0, 'Erin'),
(1646, 'tjamail', '$P$B04ZOqiiPsELgDTDvxFnSI/07I8BpQ.', 'Travis Jamail', 'parrothead00@gmail.com', '', '2012-07-12 01:00:00', '', 0, 'Travis'),
(1647, 'lwalsh', '$P$BZAzu0yVHEXc/TKsIzXvdXSAENLytb/', 'Lee Anna Walsh', 'leeannawalsh@hotmail.com', '', '2012-09-11 00:00:00', '', 0, 'Lee Anna'),
(1648, 'jimsmit', '$P$BWLuurFIsVsUUBhCvUQwlfwXI811tQ.', 'Jim Smith', 'jimsmith@propmngt.com', '', '2012-12-16 20:52:28', '', 0, 'Jim');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
