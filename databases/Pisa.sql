-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: Pisa
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `landen`
--

DROP TABLE IF EXISTS `landen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landen` (
  `land` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `code` varchar(8) NOT NULL,
  `continent` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landen`
--

LOCK TABLES `landen` WRITE;
/*!40000 ALTER TABLE `landen` DISABLE KEYS */;
INSERT INTO `landen` (`land`, `code`, `continent`) VALUES ('Albania','ALB','Europe'),('United Arab Emirates','ARE','Asia'),('Argentina','ARG','South America'),('Australia','AUS','Oceania'),('Austria','AUT','Europe'),('Belgium','BEL','Europe'),('Bulgaria','BGR','Europe'),('Brazil','BRA','South America'),('Canada','CAN','North America'),('Switzerland','CHE','Europe'),('Chile','CHL','South America'),('China','CHN','Asia'),('Colombia','COL','South America'),('Costa Rica','CRI','North America'),('Cyprus','CYP','Europe'),('Czechia','CZE','Europe'),('Germany','DEU','Europe'),('Denmark','DNK','Europe'),('Dominican Republic','DOM','North America'),('Algeria','DZA','Africa'),('Spain','ESP','Europe'),('Estonia','EST','Europe'),('Finland','FIN','Europe'),('France','FRA','Europe'),('United Kingdom','GBR','Europe'),('Georgia','GEO','Asia'),('Greece','GRC','Europe'),('Hong Kong','HKG','Asia'),('Croatia','HRV','Europe'),('Hungary','HUN','Europe'),('Indonesia','IDN','Asia'),('Ireland','IRL','Europe'),('Iceland','ISL','Europe'),('Israel','ISR','Asia'),('Italy','ITA','Europe'),('Jordan','JOR','Asia'),('Japan','JPN','Asia'),('Kazakhstan','KAZ','Asia'),('South Korea','KOR','Asia'),('Lebanon','LBN','Asia'),('Lithuania','LTU','Europe'),('Luxembourg','LUX','Europe'),('Latvia','LVA','Europe'),('Macao','MAC','Asia'),('Moldova','MDA','Europe'),('Mexico','MEX','North America'),('North Macedonia','MKD','Europe'),('Malta','MLT','Europe'),('Montenegro','MNE','Europe'),('Malaysia','MYS','Asia'),('Netherlands','NLD','Europe'),('Norway','NOR','Europe'),('New Zealand','NZL','Oceania'),('Kosovo','OWID_KOS','Europe'),('Peru','PER','South America'),('Poland','POL','Europe'),('Portugal','PRT','Europe'),('Qatar','QAT','Asia'),('Romania','ROU','Europe'),('Russia','RUS','Europe'),('Singapore','SGP','Asia'),('Slovakia','SVK','Europe'),('Slovenia','SVN','Europe'),('Sweden','SWE','Europe'),('Thailand','THA','Asia'),('Trinidad and Tobago','TTO','North America'),('Tunisia','TUN','Africa'),('Turkey','TUR','Asia'),('Uruguay','URY','South America'),('United States','USA','North America'),('Vietnam','VNM','Asia');
/*!40000 ALTER TABLE `landen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `score`
--

DROP TABLE IF EXISTS `score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `score` (
  `code` varchar(8) NOT NULL,
  `jaar` int NOT NULL,
  `vrouw` varchar(18) DEFAULT NULL,
  `man` varchar(18) DEFAULT NULL,
  `inwoners` int DEFAULT NULL,
  PRIMARY KEY (`code`,`jaar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `score`
--

LOCK TABLES `score` WRITE;
/*!40000 ALTER TABLE `score` DISABLE KEYS */;
INSERT INTO `score` (`code`, `jaar`, `vrouw`, `man`, `inwoners`) VALUES ('ALB',2000,'377,55407449987604','319,07057681973',3129246),('ALB',2009,'416,70712791715','354,51500465301905',2973044),('ALB',2012,'401,48297910331','386,93174838675696',2914091),('ALB',2015,'434,63962554673697','375,75919916958003',2890524),('ARE',2009,'460,353413791067','402,39080076936204',7917368),('ARE',2012,'468,785252970132','413,456297101051',9141598),('ARE',2015,'458,28702852013396','408,10532455675906',9262896),('ARG',2000,'437,456756967273','393,41176067474504',36870796),('ARG',2006,'399,105551971482','345,20232471095596',39289876),('ARG',2009,'415,178249870315','378,646878719441',40482786),('ARG',2012,'414,251513601116','376,62691611461304',41755188),('ARG',2015,'432,95807959441004','416,966607209736',43075416),('AUS',2000,'546,251222662044','512,64547082079',18991434),('AUS',2003,'545,4251236','506,08569569',19624163),('AUS',2006,'531,754387941855','494,872863515713',20526300),('AUS',2009,'532,853721152295','496,143990559489',21750852),('AUS',2012,'529,542280630439','495,089559442127',22903951),('AUS',2015,'518,865799240277','487,18552546683',23932499),('AUT',2000,'509,16800212637605','475,770710726621',8069276),('AUT',2003,'514,35334984','467,13176158',8175855),('AUT',2006,'512,912206518233','468,31168750743797',8285344),('AUT',2009,'490,487348248423','449,295830314651',8372657),('AUT',2012,'508,021472868237','471,093138605272',8502230),('AUT',2015,'495,075191103096','474,84603160929396',8678667),('AZE',2006,'363,130523375571','343,476240706962',8630153),('AZE',2009,'373,594303090771','349,98897471305605',8924383),('BEL',2000,'525,208512015887','492,394742587289',10282046),('BEL',2003,'526,22570572','489,32656468',10419029),('BEL',2006,'521,7283144874659','481,99719638736804',10619484),('BEL',2009,'519,806073605777','492,673631731633',10859934),('BEL',2012,'524,602442035698','492,75522340826797',11085355),('BEL',2015,'506,638598758987','490,66415578399904',11287931),('BGR',2000,'454,707266384277','407,479919373825',7997951),('BGR',2006,'432,106061134617','373,835417500126',7631020),('BGR',2009,'460,786699620653','399,64896365669296',7473509),('BGR',2012,'472,174645857154','402,547457491545',7334007),('BGR',2015,'456,59856149546096','409,44977956391403',7199739),('BRA',2000,'404,302980672766','387,636777336646',174790339),('BRA',2003,'418,85041418','384,21793132',181809244),('BRA',2006,'407,553196953584','375,806611785661',188167353),('BRA',2009,'425,183035279245','396,557736265922',193886505),('BRA',2012,'424,733890691266','394,183736196679',199287292),('BRA',2015,'418,56168221503304','395,46325493321405',204471759),('CAN',2000,'551,1331440619571','518,922764159881',30588379),('CAN',2003,'545,52538844','513,99750217',31488046),('CAN',2006,'543,043598505039','511,14244859191706',32536994),('CAN',2009,'541,527233152869','507,182200266635',33746093),('CAN',2012,'540,656756392482','505,51219875740503',34922031),('CAN',2015,'539,762385696625','513,535458040781',36026668),('CHE',2000,'510,022852598414','480,054047160789',7143764),('CHE',2003,'517,48535392','481,98973159',7268363),('CHE',2006,'515,171890018175','484,35339407068494',7457961),('CHE',2009,'520,239946603971','481,440771562667',7713902),('CHE',2012,'527,063513543594','491,074229803248',8008006),('CHE',2015,'505,373159854273','480,07952432158095',8296775),('CHL',2000,'421,10117507658697','396,49397732501603',15342350),('CHL',2006,'451,251972843165','434,290607631363',16354507),('CHL',2009,'460,620969593218','438,552452738162',16886184),('CHL',2012,'452,37114582545195','429,69617244475603',17400359),('CHL',2015,'464,561642014448','452,64224165862703',17969356),('CHN',2009,'575,566330990162','535,730940419694',1361169410),('CHN',2012,'581,299292015461','557,268698314692',1384206408),('CHN',2015,'502,558576871241','486,398966974565',1406847868),('COL',2006,'393,913354544062','375,262053156455',43200901),('COL',2009,'417,60849127619906','408,31151988896005',44750054),('COL',2012,'412,136656006929','393,592847728228',46075721),('COL',2015,'432,281926359328','416,681626098461',47520667),('CRI',2009,'449,358885792115','434,93774754059194',4520739),('CRI',2012,'452,3086499442','427,23754168740896',4688003),('CRI',2015,'434,87483235907405','419,86048650955706',4847805),('CYP',2012,'481,475843586035','417,57104191701006',1135046),('CYP',2015,'468,65833728469596','416,827051626442',1160987),('CZE',2000,'510,081929697817','472,63983771146104',10289374),('CZE',2003,'504,39583327','473,10385769999993',10239131),('CZE',2006,'508,607677810136','462,841701611321',10298609),('CZE',2009,'503,97955060449203','455,514387871759',10488155),('CZE',2012,'512,79019320583','473,98336217376703',10581302),('CZE',2015,'500,65265985875703','474,54746437791397',10601390),('DEU',2000,'502,19789409512003','467,55193256555197',81400883),('DEU',2003,'512,92705115','470,80436007',81614371),('DEU',2006,'516,620373098689','474,59980290570394',81472235),('DEU',2009,'517,5894114033059','477,883877631498',80899961),('DEU',2012,'530,119112837471','485,999706765941',80972629),('DEU',2015,'519,6741086901529','498,90214629098',81787411),('DNK',2000,'510,246251484215','485,409289248646',5341192),('DNK',2003,'504,79636891','479,38654386',5386968),('DNK',2006,'509,298657946283','479,50338265001',5444293),('DNK',2009,'509,188081274811','480,353046034598',5526389),('DNK',2012,'511,5288551769901','480,890313479441',5610909),('DNK',2015,'510,951613975922','488,78164045886103',5688695),('DOM',2015,'372,780582759786','342,16815486044703',10281675),('DZA',2015,'366,20816679808604','335,185435908668',39728020),('ESP',2000,'505,351462366416','481,215046948871',40824745),('ESP',2003,'499,78328922','460,65517300000005',42596455),('ESP',2006,'478,72493308715997','443,32930044349604',44728561),('ESP',2009,'495,73551944601303','466,820403217635',46583566),('ESP',2012,'502,510818315018','473,806246243433',47063059),('ESP',2015,'505,68471993828797','485,441144637477',46671919),('EST',2006,'524,186345121272','478,409072387135',1349373),('EST',2009,'523,8370367429351','479,649257593066',1336140),('EST',2012,'537,832866645619','494,31902810689496',1323163),('EST',2015,'533,3619841659901','505,486325650291',1315330),('FIN',2000,'571,363658475744','520,115621631106',5187953),('FIN',2003,'565,41155295','521,39035149',5227106),('FIN',2006,'571,987035693008','521,392277267547',5277490),('FIN',2009,'563,4818141669','508,386874145001',5342266),('FIN',2012,'555,708153997925','494,006461161511',5414769),('FIN',2015,'550,511163985695','503,97458303030703',5481128),('FRA',2000,'519,062923425779','490,29942018636297',59015092),('FRA',2003,'514,29450601','476,09997366',60251591),('FRA',2006,'504,62898853016','469,77237223272795',61508924),('FRA',2009,'515,181137525075','475,042340819672',62542883),('FRA',2012,'526,765410016808','482,971515216203',63564224),('FRA',2015,'513,764019445618','484,629285665973',64453194),('GBR',2000,'537','512',58923305),('GBR',2003,'520,37309215','491,82340806',59561429),('GBR',2006,'509,530709548206','480,369916150736',60821349),('GBR',2009,'506,51286510323405','481,383127981445',62828620),('GBR',2012,'511,533774403832','486,630844516237',64525300),('GBR',2015,'509,09039183937006','487,19610772612',65860149),('GEO',2009,'404,98527420745796','344,329198152619',4119490),('GEO',2015,'431,881965958212','373,758526989662',4024180),('GRC',2000,'492,71948762215897','455,676309645621',11082103),('GRC',2003,'490,3706086','452,88274315',11218879),('GRC',2006,'488,148883137093','431,56534564418',11185231),('GRC',2009,'505,92506925774694','458,786239637549',10959268),('GRC',2012,'502,197788793413','451,71835153824503',10781119),('GRC',2015,'486,460030349618','449,136246082641',10659737),('HKG',2000,'533,310325952124','517,649036013134',6606328),('HKG',2003,'525,35953263','493,82586699',6724687),('HKG',2006,'551,433366080495','520,264760571241',6802083),('HKG',2009,'550,359626064377','517,844115446642',6924642),('HKG',2012,'558,257725781634','532,8349345467241',7046847),('HKG',2015,'540,984419840984','512,711333165103',7185992),('HRV',2006,'502,270260814916','452,417730819469',4370782),('HRV',2009,'502,850718629984','451,700568138285',4341261),('HRV',2012,'509,180277340374','460,899420459168',4295869),('HRV',2015,'499,585792381621','473,136689080089',4232874),('HUN',2000,'496,167592021022','464,543413672032',10220509),('HUN',2003,'498,20472566','467,23502392',10141341),('HUN',2006,'502,97757705003096','463,41113947723505',10055657),('HUN',2009,'513,254288493717','475,421199675159',9959439),('HUN',2012,'507,54886756225403','467,963663094495',9864363),('HUN',2015,'481,959565026027','457,137732618274',9777925),('IDN',2000,'380,47912145736603','360,288761021369',211513822),('IDN',2003,'393,52329187','369,48430178',220309473),('IDN',2006,'402,122806525954','384,202624384024',229318262),('IDN',2009,'419,801980605187','383,272320466674',238620554),('IDN',2012,'410,40847556539904','382,254098784263',248451714),('IDN',2015,'408,999416848619','385,564158426879',258383257),('IRL',2000,'541,518331624053','512,8387992476249',3783095),('IRL',2003,'530,10087292','501,08396794',3980077),('IRL',2006,'534,001287404319','500,2273155754',4230619),('IRL',2009,'515,47848473239','476,29645467120696',4494572),('IRL',2012,'537,668885369705','509,15253459194804',4608199),('IRL',2015,'526,949086093744','514,991400043242',4652420),('ISL',2000,'528,12896665','488,44772195251505',280439),('ISL',2003,'521,56618226','463,80533569',287955),('ISL',2006,'508,865418260745','460,41068036391295',299727),('ISL',2009,'522,245050522895','478,07470161491204',316057),('ISL',2012,'508,38984713435997','457,27483893534696',325642),('ISL',2015,'501,716703123275','460,103601546145',330237),('ISR',2000,'459,39328708551903','443,836529516876',5945949),('ISR',2006,'459,5382849014799','417,48533646382896',6680641),('ISR',2009,'494,737000206569','452,480780170202',7190037),('ISR',2012,'507,451094396752','463,459861684784',7614946),('ISR',2015,'490,16495419529997','467,30255420849096',7978496),('ITA',2000,'507,36951355879404','469,204101668924',56692178),('ITA',2003,'494,58509469','455,24054941',57564589),('ITA',2006,'488,976680602498','447,698793946705',58542619),('ITA',2009,'509,547561420027','463,833018969986',59105622),('ITA',2012,'509,945279912591','470,938185157837',59879469),('ITA',2015,'492,709122430036','476,70380277987897',60578489),('JOR',2006,'428,13430605063996','372,766877352902',5991547),('JOR',2009,'433,63487982109297','376,801449857788',6893258),('JOR',2012,'435,999123544892','361,16744729182506',8089963),('JOR',2015,'443,59639429813905','371,9123759217',9266573),('JPN',2000,'536,91220435395','507,251320365945',127524168),('JPN',2003,'508,98189435','486,56573804',128058368),('JPN',2006,'513,285637877579','482,708136299478',128422740),('JPN',2009,'539,934740674152','501,0229705574599',128555196),('JPN',2012,'550,723537115176','526,617773627005',128423571),('JPN',2015,'522,655280900997','509,37402705967696',127985139),('KAZ',2009,'412,09242329336','369,345371400395',16043015),('KAZ',2012,'411,084097859834','374,249647995821',16751523),('KAZ',2015,'435,383616649192','419,42822089318605',17572010),('KGZ',2006,'308,478126084425','257,387479104924',5124379),('KGZ',2009,'339,840921878933','286,636246401846',5334708),('KOR',2000,'532,71562446411','518,502628907568',47379237),('KOR',2003,'546,73116464','525,47687253',48260901),('KOR',2006,'573,781294077605','538,75514407964',48880449),('KOR',2009,'557,982544274323','522,503358452028',49347450),('KOR',2012,'548,20669067247','524,964649363514',50060639),('KOR',2015,'538,609217621669','498,06898792960703',50823087),('LBN',2015,'353,27729565141095','338,806193391312',6532681),('LIE',2000,'499,612128788315','468,44527611412997',33184),('LIE',2003,'533,99660661','516,6032106',34172),('LIE',2006,'531,0754534693859','486,381788419409',34975),('LIE',2009,'516,482580309676','484,098002030712',35723),('LIE',2012,'528,535734125908','504,06625500528503',36615),('LTU',2006,'496,24639906185797','444,822183814347',3303329),('LTU',2009,'498,22332043400803','439,48333492615194',3167270),('LTU',2012,'505,06983263794194','449,964837131296',3045561),('LTU',2015,'492,242317756274','453,15666430945504',2931872),('LUX',2000,'456','429',436106),('LUX',2003,'495,66350551','462,65919496',447317),('LUX',2006,'495,39947682420404','463,715020566461',465611),('LUX',2009,'492,136453879663','452,73317669323797',496536),('LUX',2012,'503,02352797776297','473,045665549999',530855),('LUX',2015,'492,03480002090106','470,697006926094',566741),('LVA',2000,'484,710510453745','431,91829017475305',2384150),('LVA',2003,'509,14276672','470,39687623',2305845),('LVA',2006,'503,803591197326','453,828276200184',2225063),('LVA',2009,'507,342494975563','459,95212844240405',2144784),('LVA',2012,'516,306215325392','461,57431810709005',2069018),('LVA',2015,'508,825073925476','466,75521735181997',1997675),('MAC',2003,'504,09074316','490,82096576',460157),('MAC',2006,'505,473136467405','479,42192326748494',493804),('MAC',2009,'503,93641359271203','469,750243171745',526401),('MAC',2012,'527,226938483342','491,573809245031',564037),('MAC',2015,'524,538927488611','492,96136830665',602093),('MDA',2009,'411,292993387932','366,09308724943804',4097515),('MDA',2015,'442,185002049614','390,49216793877997',4070705),('MEX',2000,'431,746581483622','411,481085458806',98899845),('MEX',2003,'410,06540127','388,58905488',103081020),('MEX',2006,'426,66666585079696','393,06171054142095',107560155),('MEX',2009,'437,578499956016','412,653200518897',112463886),('MEX',2012,'435,27249902974097','411,36547045664804',117274156),('MEX',2015,'431,23221421871597','415,539824738972',121858251),('MKD',2000,'399','348',2034823),('MKD',2015,'375,83795886765404','330,135022649236',2079335),('MLT',2009,'477,98719156402603','405,59319237244796',411559),('MLT',2015,'468,058100732441','425,91005943794295',433559),('MNE',2006,'415,254377269028','370,180462264694',617863),('MNE',2009,'434,454128518583','381,878035111267',622933),('MNE',2012,'452,99490608990305','391,25939441790604',625931),('MNE',2015,'444,17908804103104','410,350380375567',626957),('MUS',2009,'426,090138978583','386,06227542512903',1243996),('MYS',2009,'430,874191681046','396,14737190125703',27735038),('MYS',2012,'417,57543674221','377,495277162119',29068189),('MYS',2015,'445,356703175277','414,125865882839',30270965),('NLD',2003,'523,77815084','502,87178551',16200948),('NLD',2006,'519,04558339725','494,894625422477',16440091),('NLD',2009,'520,50856281969','496,168243449476',16626379),('NLD',2012,'524,760775713203','498,317400048342',16791850),('NLD',2015,'514,703455614347','491,136710586278',16938492),('NOR',2000,'528,817850949747','485,62228172292504',4499375),('NOR',2003,'524,54252841','475,34477664',4570096),('NOR',2006,'508,041622125272','462,097166111139',4672986),('NOR',2009,'527,397287473271','480,123258132742',4826847),('NOR',2012,'527,771189324059','481,28400186706597',5013716),('NOR',2015,'533,355999285262','493,536736235094',5199827),('NZL',2000,'552,620956569894','506,78812250154505',3858992),('NZL',2003,'535,35267169','507,72556731',4022074),('NZL',2006,'539,11765330395','501,74025960697196',4185881),('NZL',2009,'544,175237390207','498,52460079205',4323338),('NZL',2012,'529,765066356803','495,365258252071',4468462),('NZL',2015,'525,519854165388','493,22578910134104',4614527),('OWID_KOS',2015,'365,359564498605','329,458042720406',NULL),('PAN',2009,'387,194532513983','354,026894186951',3579215),('PER',2000,'330,492418898531','323,938993789359',26459944),('PER',2009,'380,88344166742104','358,730366466045',28792663),('PER',2012,'394,700707684305','372,988714849909',29506790),('PER',2015,'401,424690050047','393,695225017837',30470739),('POL',2000,'497,497816069077','461,3707822672',38556699),('POL',2003,'516,32781003','476,77713052',38441821),('POL',2006,'527,565470037154','487,441907139635',38354447),('POL',2009,'525,323156304448','475,659963024139',38351924),('POL',2012,'538,686545859497','496,705990262434',38227033),('POL',2015,'520,674640159029','491,244641733596',38034076),('PRT',2000,'482,37017319677','457,695689129605',10297117),('PRT',2003,'494,86158237','458,52032338',10429615),('PRT',2006,'488,168148527388','455,32811992132895',10542837),('PRT',2009,'507,934143837033','469,879152219618',10604066),('PRT',2012,'507,56209861015196','468,390312516233',10526308),('PRT',2015,'506,551766809183','489,86289664579596',10368346),('QAT',2006,'345,536918778118','279,656814665426',1022704),('QAT',2009,'397,293103226206','347,075778142724',1654944),('QAT',2012,'423,730057491305','353,525699394881',2196078),('QAT',2015,'428,954625568984','376,07660859259397',2565708),('ROU',2000,'434,380366062502','420,752430210743',22137423),('ROU',2006,'417,860460768791','373,834686978247',21234312),('ROU',2009,'445,337077778124','402,793239680215',20637995),('ROU',2012,'457,367840987018','417,001068587005',20227467),('ROU',2015,'442,383386763647','424,766577343496',19925182),('RUS',2000,'481,014928738959','442,831320548918',146404890),('RUS',2003,'456,36305548','427,84010293',144610876),('RUS',2006,'458,01821641337006','420,113453096052',143403258),('RUS',2009,'481,522380082929','436,875482356164',143326904),('RUS',2012,'495,143866292427','455,146624925589',143993888),('RUS',2015,'507,483307009467','481,37434995342795',144985059),('SGP',2009,'541,762383042989','510,55538447392195',4966614),('SGP',2012,'558,5959519825981','526,507474275712',5369469),('SGP',2015,'545,558478749209','525,315266235515',5592143),('SRB',2003,'433,04892505','389,93318494',9292232),('SRB',2006,'422,156220002313','380,54279902950697',9145913),('SRB',2009,'461,83647093560097','422,37902650371495',9023354),('SRB',2012,'469,110911413917','422,93595401439103',8940116),('SVK',2003,'485,82025876','453,27861292',5399844),('SVK',2006,'487,75783759410996','446,10492009768205',5398674),('SVK',2009,'502,87442276083704','451,553695149868',5401149),('SVK',2012,'483,30310649774805','444,08067674375803',5414894),('SVK',2015,'470,862720645183','435,24882350775397',5435614),('SVN',2006,'521,105266853565','467,498138910692',2002427),('SVN',2009,'511,02989692265703','456,218708899495',2033807),('SVN',2012,'510,14953965990895','454,465524112331',2057826),('SVN',2015,'527,553869336165','484,309852913956',2071199),('SWE',2000,'535,593572618232','498,630112798858',8881642),('SWE',2003,'532,65924586','495,91309064',8951439),('SWE',2006,'528,058807667222','487,590444744611',9096170),('SWE',2009,'520,559413719438','475,05130311229897',9313085),('SWE',2012,'509,134939233387','457,99112590579904',9542817),('SWE',2015,'519,949814630651','480,71827843959295',9764949),('THA',2000,'447,639530402166','406,460689305223',62952639),('THA',2003,'439,16775604','396,44662332',64549867),('THA',2006,'439,866549066755','385,566758187838',65812539),('THA',2009,'437,65598704625296','400,01215841115305',66866834),('THA',2012,'465,4289404627901','410,43655651905505',67835969),('THA',2015,'422,553259828534','391,584783981752',68714519),('TTO',2009,'445,116986679658','387,242004239606',1320921),('TTO',2015,'452,441166353584','401,459938944018',1370332),('TUN',2003,'387,10321092','361,77026888',9945282),('TUN',2006,'398,347591752126','360,653715655027',10201211),('TUN',2009,'418,46956389706804','387,27932246569003',10525691),('TUN',2012,'418,32435920170997','387,77304477615996',10846993),('TUN',2015,'372,672552034704','347,60383061838303',11179951),('TUR',2003,'459,3116041999999','425,97298735',66089402),('TUR',2006,'471,04241606766703','427,349857335057',68756809),('TUR',2009,'486,300444483134','443,460801349424',71321406),('TUR',2012,'498,639974431596','452,843130763133',74651046),('TUR',2015,'442,246080560628','414,43958350324897',78529413),('URY',2003,'453,31831466','414,02458746',3323661),('URY',2006,'434,578215584637','389,398924895245',3325403),('URY',2009,'445,424598485964','403,72036347592',3349676),('URY',2012,'427,980512859511','392,49066433493994',3378975),('URY',2015,'447,71960406734803','424,296889214077',3412013),('USA',2000,'518,241649855318','489,675253862099',281710914),('USA',2003,'511,30285221','479,28796396',289815567),('USA',2009,'512,518387767396','487,78848919858',306307565),('USA',2012,'513,268608058245','482,498456630504',314043885),('USA',2015,'506,975196611877','486,90141162662',320878312),('VNM',2012,'522,529892026495','491,72652118760806',89801926),('VNM',2015,'499,038588841179','473,91912883491796',92677082);
/*!40000 ALTER TABLE `score` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-02 19:54:02
