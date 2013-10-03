<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\FormBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\OrganisationunitBundle\Entity\OrganisationunitCompleteness;
use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\OrganisationunitBundle\Entity\OrganisationunitStructure;
use Hris\UserBundle\Entity\User;

class LoadOrganisationunitData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Dummy organisationunit tree
     * 
     * @var organisationunits
     */
    private $organisationunits;

    /**
     * Dummy indexedOrganisationunit tree
     *
     * @var indexedOrganisationunits
     */
    private $indexedOrganisationunits;

    /**
     * Dummy index
     *
     * @var index
     */
    private $index;

    /**
     * Dummy organisationunitNames
     *
     * @var organisationunitNames
     */
    private $organisationunitNames;

    /**
     * Mininum number of dispensaries to generate
     *
     * @var minDispensaryCount
     */
    private $minDispensaryCount;

    /**
     * Mininum number of min. HealthCentreCount to generate
     *
     * @var minHealthCentreCount
     */
    private $minHealthCentreCount;

    /**
     * Mininum number of hospitals to generate
     *
     * @var minHospitalCount
     */
    private $minHospitalCount;

    /**
     * Maxinum number of dispensaries to generate
     *
     * @var maxDispensaryCount
     */
    private $maxDispensaryCount;

    /**
     * Maxinum number of max. HealthCentreCount to generate
     *
     * @var maxHealthCentreCount
     */
    private $maxHealthCentreCount;

    /**
     * Maxinum number of hospitals to generate
     *
     * @var maxHospitalCount
     */
    private $maxHospitalCount;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->index = 0;
        // Dispensaries
        $this->minDispensaryCount=10;
        $this->maxDispensaryCount=15;
        // Health centres
        $this->minHealthCentreCount=5;
        $this->maxHealthCentreCount=10;
        // Hospitals
        $this->minHospitalCount=2;
        $this->maxHospitalCount=5;

        $this->indexedOrganisationunits = Array();
    }

    /**
     * Returns Array of organisationunit fixtures
     *
     * @return mixed
     */
    public function getOrganisationunits()
    {
        return $this->organisationunits;
    }

    /**
     * Returns Array of indexedOrganisationunits
     *
     * @return mixed
     */
    public function getIndexedOrganisationunits()
    {
        return $this->indexedOrganisationunits;
    }
    
    /**
     * Returns Array of organisationunitNames fixtures
     *
     * @return mixed
     */
    public function getOrganisationunitNames()
    {
        return $this->organisationunitNames;
    }

    public function addDummyOrganisationunitNames()
    {
        $this->organisationunitNames = Array(
            "Kimange","Lesoi","Visezi","Nahimba","Lumeji","Ngoyoni","Sumve","Isumbwe","Mahanje","Kilimilile","Manchali","Bakwata B Nyasubi","Diyoma","Uru Kaskazini","Ruganzu","Gwandi Chamwino","Pilila","Mang`ola Barazani","Nyaruboza","Qameyu","Mahabusu","Kiberege Rural","Ochuna","Tumbili","Longalohniga","Mwanga Kkk","Nyamabuye","Mihambwe","Isandula B","Msingisi","Mwambiti","Chilonwa","Sengerema Secondary","Mandege","Kasisi","Buturi","Kining`nila","Hananasif	","Kanikelele","Mwasamba","Mji Mwema","Vidunda","Tunko","Neema Rc","Katete","Mpwapwa","Mirirani Rc","Minziro","Chajo","Sirorisimba Tmc","Kishamba","Kakesio","Luhagara","Lyoma","Lukusanguse","Lagangabilili","Bumangi Ktm","Namtumbuka","Lulu","Kiomboni","Tamato","Mwananyamala","Bakwata Usanda","Kijiweni","Mikumi","Lupapila","Mwasekagi","Magunguli","Mtakuja Namtumbo","Kahama","Mahumbika","Mdandu","Nanyogie","Tununguo","Ikuzi","Kashai","Itagano","Airport Mico","Ndongosi Songea","Ibumila","Riroda Rc","Sejeli","Mtana","Mahongole","Ndoleleji Rc","Kware","Kiegei","Mangio","Veyula","Lagangare","Mkyeku","Kambi Ya Simba","Makanya","Holyspiri","Aic Nyasho","Kwekibomi","Kitongosima","Kashozi","Mwaikisabe","Mawanjeni","Okaoni","Nambalapala","Msente","Nengo","Majimaji - Jwtz","Ntuntu","Nyamika","Mnase","Parknyigoti","Mima","Kilimarondo","Malinyi","Mayoka","Mtendeni","Rwantege","Mugana Dd","Kashaulili","Wangama","Maposeni","Chimala Missio","Alliance One","Salama A","Maghang","St. Joseph Chato","Tpc","Itipula","Ikomwa","Mkamba Private","Kwamngumi Priso","Zaac Daraja11","Mambwekenya","Isitu","Kyengege Churchofgod","Gasuma","Isangala","Msimbazi Missio","Lole Luthera","Shigalla","Vidunda Missio","Akim Maternity Home","Muzdalfa","Iyenze","Chimbendenga","Baobab","Ukalawa","Uuwo","Goweko","Isanga","Udonja","Bumbuta","Kisangaji","Bomani Magereza","Igandu","Digodigo","Matekwe","Bonchugu","Lichwachwa","Matomondo","Ziba","Chambo","Busanda","Mafene","Guduwi","Kisasatu","Ngulyati","Mbogwe","Nyalwela","Efatha","Kitembele","Misozwe","Ics Tabata","Mubanga","Lugalo","Ifuwa","Vikonje","Nyakibimbili","Kimeya","Ndembo","Isaka","Kifura","Misughaa","Berege","Yagaluka","Kiyogo","Manyemba","Chiwana","Mariestopes","Rwamkoma","Kiru","Kenyamanyori","Mkumbaru","Rosminia","Victoria","Kisumbakasote","Gininiga","Seng`wa","Sapiwi","St. Alois Rc","Busondo","Kandawale","Beatrice","Lyabusende","Nguruka","Misalai","Sindeni","Mwalisi","Kididima","Mnazi","Mbelei","Ilekanilo","Kwenangu","Kitwai","Lukunguni","Mwakilyambiti","Shabaka","Kwemkole","Mfinga","Lutete","Bakwata Na3","Madibira Small Holder","Alhillal","Chalinze","Surubu","Mruma","Dohomu","Mwakibuga","Nzega","Maswa Girls","Chombe","Moita Bwawani","Matembwe","Ubaruku","Dodoma","Pito","Naipanga","Ibwera","Milo","Kwabada","St. Vice","Diguzi","Kihereketi","Nyaishozi","Mtimbira Rural","Ttpl","Kanyinya","Karmel","Rwimbogo","Nyansurura","Kibogwa","Majembe","St Lukes Anglica","Chamabanda","Buziba","Isungang`holo","Korongwe","Temeke","Katoke","Kingerikiti","Masusante","Lugala","Kinyasi Va","Maheha","Kirando","Olkeriya","Buzirayombo","Galamba","Chitete","Meresini","Katumba Ii","Lulindi","Ovada","Ikola","Nkome","Kimambi","Ipwani","Iwambi","Galu","Mhenda","Kitere","Dr. Hammeer","Mwanakianga","Kalangasi","Kanyama","Nanjara","Masagalu","Mloda","Chato Distric","Kisongo Charitable","Iguguno Gov","Mkurumusi","St Bhakita","Lower Ruvu","Chikumbulu","Sarawe","Mamboya","Mbaru","Emaus Cogi","Chiwondo","Vuo","Misitu","Ugano","Ndago Rural","Lupondo","Izumbe Ii","Bubinza","Chigwingwili","Semeni","Nditi","Itongoitale","Kivingo","Kimani","Wami","Kaniha","Naliendele","Lifua","Kambi Ya Chokaa","Old Shinyanga Jw","Ihanda","Sazira","Kijumbula","Sukamahela","Lufu","Ruaha Al-sheikh","Kasusu","Msewe.	","Kikundi","Mountain Slopes","Shuwa","Kwashemshi","Mewada","Chifukulo","Chihanga","Milambo Itobo","Siha Distric","Mbabala A","Mwayaya","Mbalizi","Ihalula","Kalembo","Bugunda","Chiwonga","Lugata","T.m.s","Nyamalimbe","Matevesi","Njomlole","Ngamanga","Kiromo","Kidabaga","Kaigara","Ujuni","Mihale","Mgungira","Malangi","Kimnyaki","Tambukareli","Kisima","St. Gemma Gilga","Rutamba","Mgama","Ihowanza","Mkomazi","Lundu","Masama Rural","Ileje Day","Ng`anda","Piyaya","Mtanga","Mabadaga","Lubaga","Mkongo Nakawale","Mbembaleo","Bugire","Kyaka Salie","Slahhamo","Buhongwa","Kilima Moja","Malya","Shizuvi","Isupilo Magereza","Morogoro","Mahomanyika","Lumuli","Funta","Mkako","Kubiterere","Buyogo","Mkunwa","Mirembe Referral","Litorongi","Kigwa B","Lupunga","Iragua Rc","Ilongo","St.philip","Makanya Rural","Tuombemungu","Sangwe","Mtimbwa","Makosi","Solola","Taifo","Kwaga","Imalaseko","Seseko","Kashashi","Mbokomu","Ubetu","Mkokola-lwengera","Lugoba Sekondary","Bora","Nsungwa","Wazazi Ukenyenge","Gwasi Sanawari","Kinyambuli","Kiruru","Luana","Mary Benne","Idiwili","Bitare","Tongwe","Red Cross Dental Uni","Kananondo","Gendabi","Magazini","N.c.u","Sotele","Gidagamowd","Mwika","Nyalwanzaja","Nyamagaro","Buguruni Anglica","Igominyi","Mundemu","Mbwenkuru","Vuagha","Mrao Rc","Mandawa","Mangula","Lugongo","Muganza","Ruaha Mbuyuni","Jonthan Maternity","Nganza Secondary","Chiulu","Nyeregete","Mwashata","Members Nuraifo","Bo","Ngujini","Kibakwe","Misugusugu","Kuruya","Mkoya","Chazi","Kasungamile","Buhumbi","Sigili","Wazazi Isebya","Mataya","Wazazi Mwenge","Bigabiro","Iwiji","Mwabagimu","Ymca","Wazazi Bugelenga","Brigita","Ng`amba","Luguru Uw","Mayenzi","Mackey House","Kitahana","Magazini Namtumbo","Mufindi Tea-co","Movu","Mlalakuwa	","Kanyigo","Mtabila Refugee","Simike","Ilomba","Lemkuna","Ligoma","Sima","Visiga Seminary","Mweka Wildlife","Terrat Road","Iyula","Luganga","Mkali","Kibati","Zuzu","Ibililo","Lema","Horohoro","Nyantira","Mwajidalala","Kj 21","Kinyonga Distric","Mwitikira","Kilambu","Miangalua","Mangae","Msomela","Police Dom","Kemugesi","Lusilile","Mpengele","Bihawana","Udagaji","Kagu","Kiloleni Urambo","Agape","Ngarenairobi Tag","Chem Chem","Makhotea","Kaburonge","Rupungwi","Nyamisingisi","Mwakidila","Kilema Pofo","Makurugusi","Malolo","Ngoma A","Katonto","Kkkt Chamukoroma","Ikhoho","Jibondo","Mkonjowano","Ebuyu","Kasumulu","Sunzula","Goranga","Mwanambaya","Mwanga Sec","Mailisita Rc","Mcharo","Munjebwe","Nsumba Secondary","Mkungo","Mofu","Matui Azimio","Kisalala","Mulera","Kitunda Missio","Zobogo","Ttc","Uyogo","Hingawali","Majengo Kikatiti","Nkambaku","Rusumo","Huruma Kimandolu","Mabawe","Kinyangiri Rural","Nyamigamba","Silent I","Ng`ong`onha","Kitaya","Vihingo","Mwabomba","Ihahi Missio","St. Paul And Peter","Mariam Consolata","Total","Chanjale","Ntaba","Ndilima Litembo","Kambarage Police","Aga Kah","Kitange","Usule","Legeza Mwendo","Mwikantsi","Tambalale","Senjele","Goima","Newala Distric","Kingolwira","Gereza","Kalinzi","Lutindi","Shushi","Kibuye","Mwakaleli Luthera","Hope","Itulike","Ngarani Kwakifua","Kibugumo","Sengerema","Nyahua","Kibwigwa","Mbwera","Mbimbi","Minjingu","Ikunguipina","Kamachumu","Trust God","Malangali","Bugando Referral","Keisangora","Sindano","Mayombo","Nguyami","Itaja","Kibasila Jwtz","Nyansembe","Shimbi","Tindigani Maternity","Kanga","Isagwa","Huduuma","Mwamashimba","Kisaka","E.l.c.","Makukwe","Kakonko","Mavumo","Nyololo","Mundarara","Mkobwe","Ksc","Matyazo","Bwelwa","Nalingu","Makonga","Kishuro","Lunguza","Uyovu","Laja","Sumbugu","Nkotokuyana","Lilombe","Mjimwema","Moshono Arusha","Kagera Nkanda","Kimbiji","Nyankumbu","Haubi","Nyamtelela","Mwanga Rc","Hamwelo","Mwamagembe","Mkunya","Bashay","Chunga","Sasajila","Kumkugwa","Shycom","Nyankere","Mbaya","Muyama","Makiba","Mwakashindye","Narco","Mitesa","Kibiti","Machenje","Chekereni","Sanya Statio","Dr. Shivji","Mwamitilwa","Kibale","Zeze","Ganyange","Bugamba","Nangaru","Rudi","Olasiti","Nangurugai","Ukwega","Itamba","Shangani","Tazara Mlimba","Nafco","Kindimba","Kiranyi","Pangale","Nazareth","Matambwe Gr","Kibitilwa","Essilalei","Lugando","Ruvu Mferejini","Nanyanga","Buyagu","Nyakato","Ikulilo","Majimaji","Pande Jwtz","Igegu","Misinko","Msalato Jk","Juwa","Nyakitonto","Kibada","Lwanzali","Mawemilu","Mitamba","Liuli","Korogwe Girls","Kikuyu Mpwapwa","Brewaries","Tanzania Breweries","Mkuu Rc","Mbuga","Maweso","Cardinal Rugambwa","Ikombe","Kanyimbi","Kijiji","Msange","Kiluvya Jwtz","Ilasi Ii","Kagezi","Kibindu","Ngarenaro","Mwamashiga","Mvungwe","Bugango","Sagamaganga","Maretadu","Ndapata","Misechela","Arafa Mkunguni","Mgololo","Tchenzema","Kiberege Priso","Inala","Loltepesi","Kizungu","Nyakatende","Mibono","Myamitita","Lowe","Umati","Mwazye Va","Itebulanda","Lyasa Image","Mkoani","Mavanga","Isuto","Nzihi","Sunya","Katuma","Kazaroho","Naumbu","Konga","Ulumi Missio","Mlalo Rural","Malita","Mlela","Kiemba","Nghahelezi","Isikizya","Chiwale","Nangale","Ruanda","Magereza Ngara","Hafford","Nanyala","Mpirani Kkk","Isonso","Tukamisasa","Katende","Poverty Africa","Chita Jk","Mafinga","Ihanga","Mpeta","Sambaru","Sda Mbeya","Sunsi","Msia","Tayma Bububu","Klp Kiborloni","Rukaragata","Shinzingo","Dumila","Izizimba","Iwungilo","Ituha","Itinka","Mtanana","Minyinya","Mkowela","Buzuruga","Afya Care (tausi)","St, Harry","Ibwanzi","Kondoa Distric","Konje","Mwera","Masangula","Kigulu","Busegwe","Mswaha","Shengena Rural","Ipyana","Mpanda Distric","Mkokola","Mwananchi Trus","Mugombe","Nyamigogo","Busangwa","Chibelela","Bupigi","Mtuntumbe","Nansimo","Mkanga Ii","Ishinde","Kisimiri Chini","Mtandi","Nyamizeze","Masinono","Duga Maforoni","Kizaru","Milama","Kibo March","Arafa Ses","Mkoka Sda","Ngwinde","Salamabugatu","Matarawe","Nyamtukuza","Shia","Sirari","Roman Catholic","Mvomero","Buhamila","Bungurere","Murumba","Lihimalyao","Wasa","Mkumbi","Hasama","Gwanumpu","Nyerere","Luthera","Kanani","Nyamitita","Msongola","Isamilo","Lingeka","Ipinda","Mukubu","Tabora Regional(kitete)","Kiganza","Nala","Ketumbeine","Ulembwe","Kafita","Irugwa","Chimendeli","Ibwaga","Mchomoro","Lubalisi","Kigongoni","Shilembo","Igaga","Biturana","Makata","Migongo","Rugu","Farkwa Va","Jwtz Makoko","Nhobola","Mtisi","Ng`hambi","Usalule","Mafizi","Igalilimi","Lubondo","Bulinda","Bakwata Kibondo","Ivilikinge","Chikuti","Chipogoro","Kibwegere 	","Mkabogo","Makoga","Vwawa","Kidyama","Bulige","Lawate Tag","Seela Singisi","Kansay","Mangawe","Maharaka","Ilolo","Kirungu","St. Francis","Lusonga","Bumbuli","Chandama","Kilago","Lawate Red Cross","Kibao Rc","Kikombo","Bushekya","Membe","Siima","Kiseria","Wazazi Nyakato","Ikang`asi","Isevya","Makuru","Chamkoroma","Kilambo","Tanesco Kidatu","Nitekela","Mikocheni","Toloha","Singiwe","Mihama","Mafweko","Jana","Rusohoko","Uhuru Bakwata","Igola","Malindi","Kilole","Katapulo","Ilindi","Nyamtita","Swaila","Nyangere","Kinungu","Liwale Distric","Tanganyika Packers","Mico Chartable","Bunazi","Mitowo","Bahimba","Tumaini Chikundi","Nyasoro","Hindu Mandal","Ilangali","Songa","Mtamba Missio","Sokoni","Masimbwe","Tarakea","Lalago","Mhezi","Ikweha","Kabanga","Kizenga","Kalebezo","Kyomu","Chamtui","Tupendane","St. Lukes","Ununio 	","Mpata","Muluseni","Kiputa C. Adam","Muhintiri","Remng`orori","Lukasi","Nyang`holongo","Makame","Mgambazi","Kileo","Nyiboko","Habiya","Tumaini Jipya","Kahunda","Ombweya","Chiwe","Manienga","Upone","Safi Medics","Ndagoni","Mchuo","Msata","Lundamatwe","Bukama","Manyara","Endule","Usimba","Nkumba","Lukululu","Dongobesh","Galanos Sec. School","Sheyo","Igolwa","Kimotorok","Mwanhala","Masaki","Kyerwa","Milundikwa","Namanyere Dd","Nanjilinji","Mahuta","Nyumba Ya Mungu","Kitewele","Elct Moshi Luthera","Kinango","Isalavanu","Kalungu","Ilangu","Lyabukande","Mkili","Juani","Kabalenzi","Manolo","Kyamulaile","Tukuzi","Karume","Mkwawa","Mpakani","Kalunde","Busalanga","Songwe","Kidete","Kiruruma","Wazazi","Katanga","Kindimba Juu","Sungwizi","Handeni","Ihazutwa","Kitanda","Yakobi","Iseresere","Msungua","Anne","Gelai Lumbwa","Msumi","Bangwe","Matiri","Matyuku","Gedamar","Kiyombo","Msisi","Elang`atadapash","Mawemairo","Jeshini","Kiasi","Bukoli","Chamdindi","Itobo","Gantamome","Madunda","Tag Kilimanjaro","Chaume","Kisopwa 	","Mbalawala","Mititi","Marimbani","Victoria Lake","Bunamhala Mbugani","Ayalabe","Nkana","Mwashepi","Ipera","Nyegina","Mkono Wa Mara (ke)","Kanisa La Mungu","Canna","Urafiki Community","Kongombiti","Heru Ushingo","Usangi Sec","Makete","Muhimbira","Iguluba","Tanganyika","Mtamaa","Kiperesa","Irindi","Mombo","Upendo Maternity H","Sunji","Huruma Private","Ilasi","Kebanchebanche","Peramiho","Babati Tow","Mkalama Rural","Loolera","Arafa Yombo Relini","Ishihimulwa","Nachingwea","Mbalizi Missio","Tungamalenga Gv","Tunduma Moravia","Kafukoka","Mbamba","Kisegese","Mponde Mtae","Cogi Kijenge","Lwanjilo","Imbimbia","Nambunga","Kia","Kidalu","Ikombolinga","Imalakaseko","Ama","Wema","Wazazi Gallapo","Mswakini","Rwamulumba","Kabasa","St.joh","Kibao","Olkereyani","Dr Mwambe","Mgombasi","Nyarwana","Lipilipili","Ryamugabo","Sabasaba","Nyakasimbi","Wazazi Mwenzetu","Railway","Askofu Hhando","Heri","Igomaa","Nkweshoo","Mavota","Nshamba","Kumsenga","Lupembelwasanga","Msekwa","Tarakea Rc","Kapamisya","Kiyanga","Rungwa","Imalilo","Kisarawe Distric","Rwamchanga","Msindo","Manyamanyama","Kilindi Cdh","Machemba","Itambo","Kwemkomole","Bukiriro","Hunyari","Kasuku","St. Karoli Luanga","Nyamirembe","Mipa Rc","Kigara","Sakami","Salatei","Mwangikulu","Mkange","Myunga","Utengule","Lusu","Migunga","Losokonoi","Ngwala","Mpendo","Hombolo Makulu","Suluti","Matema Luthera","Nakafulu","Makumba","Mwabulimbu","Dr. Mwijage","Magugu","Kanoge","Mwambani","Mwamashele","Isenzanya","Chambalo","Mikalanga","Mindola","Iambi Elc","Usumau","Mwabaluhi","Chitare","Kiga","Lugenge","Bitale","Buhigwe","Likokona","Amani-igoma","Hantesya","Ikukwa","Chindi","Miva","Lualaje","Masieda Rc","Samora","Manyoni Distric","Kapalala","Bugarama","Ngofi","Bago","Ifinga","Igunguli","Vuje","Moshi Manicipal","Prison Butimba","Mtungulu","Kakunyu","Kiwanda","Tandala","St. Xavier","Sanya Juu Luth","Uhuru","Tumbakose","Zingiziwa","Chihanga .","Kasulwa","Mungere","Kizombwe","Wazazi Msonga","Kilakala","Menonite Nyihogo","Kiti Cha Mungu","Matonya","Ighombwe","Katahoka","Mchinga","Twabagondozi","Mapipili","Mtakuja Sikonge","Kidoka","Ebenezer","Katesh","Tawala","Kimanzichana","Bugembe","Tazara","Mico Mbagala","Chamwino Dtc","Ibosa","Namiungo","Kalagwa","Rauya","Rwenkende","Luale","Chiguluka","Hulia","Buhondo","Kasimana","Sengenya","Kajunjumele","Gesarya","Masandare","Itumpi","Mriti","Tayma Sokota","Monga","Kimuli","Amani","Ulindwanoni","Police Mc","Kinonko Jwtz","Mlevela","Matola","Afya Yako","Mbugani","Talaga","Amec","Namanyere","Ibumu","Isyaga","Namansi","Mwera Est.","Solwe","Kikumbi","Mibikimitali","Taweta","Kambikatoto","Ugembe","Namayuni","Nhinhi","Kumuhama","Sukuro","Mkonge","Ndongosi","Mandamazingira","Nyakabanga","Kongo","Malampaka Wazazi","Zoisa","Mahoma","Mvuha","Salama Kati","Health Master","Undomo","Kayenze","Luwango","Nduli","Busulwangiri","Mbagata Luthera","Kilumbi","Ditima","Lusewa","Rhotia","Jay Jay","Shishtoni","Bangalala","Utinta","Mama Paulina","Shagihilu","Luhimba","Mwangudo","Chinoje","Rusaba","Mbonile","Igoweko","Tumaini","Mbelekese","Ruhatwe","Mpirani","Kange Jwtz","Makomu","Njengwa","Bethsider","Kitengule Pr","Mkonze","St. Imaculata","Polisi","Rangwi Missio","Kilulu","Shuka","Laikala","Mvae","Kakindo","Changuge","Sekei Lcc","Bwakira Juu","Mazai","Mawenzusi","Rwamishenye","Msamaria","Maboha","Kabungu","Mabwawani","Kitelewasi","Kalobe","Bugaga","Ughandi","Kivinja A","Makole Urba","Nyanzige","Manzeye","Ngurudoto Rc","Ng`enza","Kisongo","Bulyashi","St Carolus","Mindu","Mkalama","Ilagala Priso","Pomeri","Machame","Mbonde","Nsimbo","New Hope","Gole","Heko","Tanzania Railways","Sintali","Nyanjati","Themi Ya Simba","Mburahati	","Nambunju","Ngarenanyuki Luthera","Hedaru Sec.","Msalato Magereza","Namatunu","Manundu Priso","Magereza Rusumo","Ngumbu","Mshindo Rc","Mbore","Kimundo","Bassodesh","St. Elizabeth","Igurusi","Njopeka","Ikuyu","Lutukira","Chogola","Makaa Pumuani","Lihomwelo Kkk","Ngorbob","Kantalemwa","Ruaha Missio","Nyenze","Sale","Mamba Myamba","Kalepula","Kilidu Ii","Mpindo","Ngoma B","Kikuyu","Sange .","Kihinga","Kibimba","Kanamalenga","Bukumbi","Kikore","Kalalasi","Kinamapula","Lalambe","Olele","Ngarenairobi Cogi","Ng`ora","Kitengule Ra","Njari","Isageng`he","Kisawasawa","Kisa","Kagoma","Mfriga","Sikonge Cdh","Makongoro Clinic","Ovada Va","Mkomaindo Distric","Masisiwe","Lukole","Selian -cdh","Msembe","Zinga","Guta","Aga-kha","Chabu","Kilesha","Linka","Nyamahwa","Bukandwe","Kamalendi","Fajima","Kigorosimba","Mwampulu","Kasamwa Tcmc","Irole","Mngazi","Wimba Mahongo","Kizengi","Magai","Mtambula","Matongo Gerezani","Ikindwa","Bujashi","Sukuma","Michenjele","Dr. Mamosha","Kibuko","Mbesa","Chabura","Budekwa","Pembamoto","Pangaro","Lwuhomelo","Mwasa","Huzi","Mole","Msanga-chamwino","Bugula","Ngaritati Luthera","Nguliguli","Tumaini Crc Fbo","Lufingo","Nzengelendete","Marogoro","Kaseme","Mganza","Mbweni 	","Mhale","Mwabenda","Kyamalange","Ukwavila","Kerebe","Mastasteba","Asante Nyerere","Kisaki Kituoni","Mailisita Mc","Ukwama","Kaliua","Kiabakari Jwtz","Nankanga","Bunda Ddh","Mkola","Munyegera","Makwai","Kibirizi","Kiuma","Chirombola","Leshata","Usa River Community","Meighs","Depo","Luono","Mandela Kiva","Magome","Kigaga","Upendo","Msolwa Ujamaa","Red Cross Makole","Tyma Kigilagila","Gula","Hombolo Lga","Buganzo","Njiapanda","Baga","Umoja Morogoro Dc","Mlangarini","Ikonga Mpya","Gairo Missio","Idifu","Mpepo","Kivisini","Sudi","Liuguru","Muhinda","Mkulula","Kidia","Ismail Kitinga","Nkwae","Ilima","Nkinga Fpc","Kasande Mico","Uchira Luthera","Minyughe","Jojilo","Ikoma","Mzidalifa","Kitete","Idundilanga","Mtenga","Kamgegi","Songambele Rc","Mrijo","Nzasa","G/mboto Bahari","Njia","Namanga","Bitoto","Maligisu","Himo (cogi)","Kigendeka","Majimatitu","Ngarenairobi Rc","Ilboru","Ruwe","Kaboya","Nyakahoja","Bikira La Maria","Besha","Ng`onde","Bujingwa","Mgeta","Mongo La Ndege","Qangded","Jobaj","Jinamo","Duru","Ruo","Uhafiwa","Arusha Lutheran Medical","Kigogwe","Igale","Kinyangiri Elc","Nyamijundu","Lukali","Rwambo Rural","Duga Sigaya","Pongwekiona","Ubagwe","Matuli","Toh - Luther House","Mkulima","Isabayaya","Albans","Kyela Distric","Makao Makuu Ya Jeshi","Kibara","Ibadakuli","Ugesu","Mikumi National Park","Igwamanoni","Changarikwa","Bahi Road","Ikimilinzowo","Lihale","Mputwa","Sura","Mwamatiga","Doroto","Igunya","Sayuni","Burhani Caritable","Mihuga","Ilolangulu","Mwamanga","Igalula .","Miyenze","Isalalo","Matambarale","Ilango","Ipililo","Itengule","Lugoba","Mtandi .","Wibia","Boko","Kitete Msindazi","Mabamba","Mwadui Williamso","Ndebwe","Naikula","Ijiha","Galula","Ndedo","Pombwe","Kiongera","Makambako","Police Barracks","Chagongwe","Nyabitaka","Chali","Wagete","Gallapo","Nyabibuye","Kitefu","Malowe","Mkono","Muzdalfa Charitable","Bugandika","Ibihi","Mwabuma","Ushirika","Singa","Lubiga","Ngumbulu","Arusha Laboratory","Ruhuwiko Jwtz","512 Kj","Nyanzenda","Nyawa","Mudida","Mlowa Barabarani","Mkolani","Kifanya","Byuna","Mwanyagula","Chonwe","Michese","Bonite","Getanuwas","Ubena Zomozi","Itiryo","Bama Beba","Mico Kibaha","Oldonyosambu Rural","Kivukoni","Zamzam","Al-ijumaa","Ndalambo","Masuguru","Nkwilo","Itununu","Buhingo","Mikwambe","Ndungu Rural","Mkanana","Mgao","Watani","Malatu","Mpande","Kadoto","Samazi","Bukonyo","Negezi","Sefunga/mangida","Njenga","Kunduchi","Mlimba","Mbindi","Kasekela","Bakwata Segese","Gelai Bomba","Arash","Tandale 	","Ndaga","Twinchilage","Yaleyale Puna","Mbuzii","Kipande Nkoavele","Majojoro","Nyakahura","Kwemazandu","Mtelawamwai","Kwekanga","Vutima","Kayanga","Dihimba","Ilula Mwaya","Kungwi","Uru Kyaseni","Namiegu","Kanyelele","Isingiro","Nambinzo","Bukama Sda","Moivo","Kibirashi","Mnero Missio","Masebe Ii Sda","Arafa Mbagala Kuu","Nkonko","Kibuyi","Mzundu","Lukenge","Urafiki Maternity H","Kinyagigi","Haydarer","Losaa","Mpanga Tzr","Janda","Tawa","Darajani","Kalambazite","Chamwenda","Mukatabo","Kamasi","Endabash","Msorwa","Mbuga Rc","Luguru","Nyankoronko","Kineng`ene","Boma Crt B","Monduli Juu","Esere","Donyomurua","Lyulu","Chandulu","Mwangika","Fukayosi","Ujenzi Mkoa","Puge","Kilosa Missio","Igala","Igunga","Chumbi","Kalole","Nyichoka","Igombola","Bumera","Mkupuka","Narumu Rc","Mpemba Missio","Kasu","Mwanshoma","Soni Rural","Ilorienito","Siloam","Mico Sandali","Kisilo","Irkepusi","Kwema","Bondo","Ninde","Igula","Roundtable","Gavao","Chankabwimba","Yombo Vituka","Ikizu","Kiteo","Legezamwendo","Simambwe","Njalamatata","Namagondo","M.m Kiwera","Tangazo","Ngumo","Ubenapriso","Doree","Lesiraa","Bakwata Kidatu","Ngerenanyuki","Mashati Govenme","Nanganga","Munane","Sungwi","Mkomba","Chitekete","Kibumaye","Kipunguni Tyma","Masewa","Gwnupu","Kiomboi","Iwondo","Kigembe","Lugoda","Kwamduma","Bungu","Mumba","Mwinza","Ngapa","K.k.k.t Kisarawe","Kandaga","Omurunazi","Nkuyu","Kwehangala","Kam Ilala","Masanganya","Mlowa Bwawani Gov","Rile","Mivumoni","Chunyu","Mdabulo","Bukiko","Banyibanyi","Mwangeza","Kidongozero","Sajorah","Mashua","Mwenge","Tella","Kibehe","Mbozi Missio","Rubeho","Shant Tow","Ilonga Gov","Bulumbela","Ab Dsm","Malampaka","Huruma Mirongo","Mugungira","Ibagwa","Ipilimo","Halambo","Huduma Uk","Kakiro","Ugala","Mwamanota","Ghalunyangu","Mother Care","Hindiwashi","Lituhi","St. Bernard","Karamba","Kwala","Nyamburi","Mbemba","Ihongole","Turiani","Bukombe","Mbwawa","Kingurundundwa","Fadhili","Trcs Langoni","Bunamhala-chuoni","Bombambili","Ukonga Ffu","Suji Sda","Bushasha","Nyang`hwale","Nyarugusu","Mtanza","Staff I","Likolombe","Nangombo","Arafa Ving. Mtakuja","Masqaroda","Mateka A","Mfumbi","Marui","Mwenzele","Mzeri","Muguda","Mbambara","Msangano","Endamaghang","Chivu","Mwisi","Mazwi","Dodoma Regional","Ngulu Rc","Iziwa","Market Stree","Ngwala Priso","Seka","Nkuhi","Oljoro 5","Tanga","Mpambala","Mpapa","Mpigamiti","Iyogelo","Lukwego","Wenje","Kamawe","Kahumulo","Ichwankima","Utelewe","Chonga","Kalemela","Ng`ingula","Kibakwe Rc","Holly Family","Kokirie","Sinya","Mitwero Ffu","Tete","Kasoli","Police Line","Buganguzi","Kasamwa","Likuyu Fusi","Totowe","Chome","Magadini","Mlambo","Kwang`andu","Migambo","Igauri","Majiri","Ulinji","Ilawa","Kabanga Missio","Pangawe Governme","Simbanguru","Kambangwa","Gitu","Maholong`wa","Sizu","Mzalendo","Mapamba","Ndumbwi	","Mwamanongu","Tenende","Nkangamo","Ruiwa","Bukokwa","Kasokola","Busi Rhc","Barikiwa","Tumaini .","Kizuka","Mkutano","Mpwayungu Rhc","Lutusyo","Olmotonyi","Mageseni","Kelle","Wangutwa","Saba Saba","Ulumi","Ngasaro","Bugorola","Nyaburundu","Ihowa","Ruvu Remi","Kianda","Umoja","Tcmc","Waama","Maganjo","Ngaiti","Mzambarauni","Mwime","Madalena Kolping","Kamsekwa","Uchira","Ichesa","Runazi","Mahiga","Uturo","Ihega","Bulima","Mwamila","Mwabulenga","Igosi","Chibingo","Kipande","Bulangamilwa","St. Catherine","Upendo Chato","Rung`abure","Moleti","Isesa","Chiwezi","Njombe Regional","Mshani","Malolwa","Lionja","Itagutwa","Lukuledi","Kifumbe","Solwa","Selela","Ngorambi","24 Kj","Mashewa","Mandaka Mnono","Boza","Dareda Kati","Rubale","Olchoronyori","Kiwira Fores","Njisi","Wamata","Mango","Bogolwa","Kazilamihunda","Kihwera","Vudee Luthera","Mwaru","Sakila","Panda Hill","Chinugulu","Ndolwa Rural","Nazareti B","Kabuhima Bakwata Na1","Mwegoha","Mitobo","Prince Saudi","Igulumuki","Lumba Chini","Kamagambo","Bweni","Ngarambi","Kandashi","Makoko Rc","Isimike","Longuo","Nyamhoza","Nyakipambo","Shufaa Charitable","Nambaya","Handali Rhc","Mtego Wa Simba","Makanya Cogi","Zainabya","Karago","Ngelenge","Walla","Pemba","Mgongo Rc","Dumbechand","Butarama","Rc/k/ndege","Mwongozo","Busunzu","Maria Emaculate","Uyowa","Mwasala","Luhanga","Mjughuda","Marungu","Kibungo Kungwe","Tindiga","Ngana","Anamid","Ulaya","Merenga","Kilulu Rc","Sango","Tow","Isagehe","Kashish I","Tanga Central","Chikuyu","Limbula","Maburi","Isakamaliwa","Tunguli","Kapelekesi","Lendanai","Sunga","Mkuu","Sibwesa","Mlanzi","Mbatakero","Kidele","Ibongoya","Kidodi","Utwango","Bunju 	","Jabal Hilal","Mlali","Oljoro Road","Masenge","Ikiwu","Kitema","Sogoso","Ifumbo","Ludende","Kibole","Chidede","Kitamabondeni","Bulanga","Mpapura","Kigangama","Boma","Mwanzaburiga","Kasense","Kerenge","Matepwende Ligera","Mtita","Kisesa `a`","Nyamadoke","Litehu","Rasheed","Mawengi","Madibila","Igekemaja","Crct Mbuyuni","Kyimo","Katoro Bukoba","Miswaki","Manu","Nyakasanda","Londoni","Atta","Makoko","Nazareti A","Kirua","Igombavanu","Isangha","Buzegwe","Lukanga","Chambezi","Namwanga","Mbwila","Usuka","Ikonda","Chikombo","Mwembeni","Bukene","Chingungwe","Lulago","Chogo","Nsheshe","Kwediboma","Malagano","Nabuhima","Kongei","Vikawe","Ihawaga","Neema","Matamba","Kitai","Chogoali","Rugasha","Namawala","Mitwero","Makanjaula","Bumeera","Kioga","Madaba","Shalom","Luswisi","Kihonda Magereza","Itagata","Kalemela A","Itimba","Menonite Kagongwa","Somo","Ryamisanga","Usambara","Mtiniko","Nduruma","Arafa Samasi","Mkoma I","Kimbuga","Lupaso","Lupalilo","Magamba","Mtawanya","Lositete","Harbanghe","Guluka Kwa Lala","Mnzavas","Kidugalo","Moyo Safi Wa Maria","Sangasanga Jwtz","D","Mkumbululu","Lihagule","Iduo","M/mhalule","Magole","Nyambili","Ikule","Namwangwa","Mawambala","Kakese","Usoke Mlimani","Manzabay","Buruma","Itololo Va","Olkokola","Kilimani","Tumati","Lilombwi","Isale","Ndungungu","Tanga Medicare","Ebrahim Haji","Tamasenga","Negero","Malibwi","Kikota","Rukoma","Igurubi","Kwakibuyu","Nyombo","Marangu","Tunduma","Chinongwe","Narumu Gov","Karangai","Makawa","Kilindi","Litowa","Mng`aro","Afya Head Quarters","Muvwa","Masanga","Iniho","Kashagulu","Ntene","Furaha","Bukundi","Itolwa","Chiuta","Upendo .","Kisimamkike","M. K","Kichiwa","Ipingo","Usisya","Kondoa Luthera","Magodi","Udekwa","Nyambuyi","Itungi","Idetemya","Ipwaga","Tinginya","Bunambiyu","Bahi Missio","Kisese Rhc","Chanhumba","Mamboleo Nurraifo","Mdende","Nambecha","Kalumbaleza","Keni","Mico Faraja","Lembeni Rc","Family Care","Karambi","Simbo","Ikolo","Masonga","Med Consul","Bonga","Nindi","Yombo Machimbo","Baranga","Kisaki Gomero","Mapojoni","Zanzui","Mpangwe","Mkalamo","Mavande","Mahale","Igongolo","Isugilo","Selian Tow","Chipole","A.i.c.t Makongoro","Ng`ombe","Inyonga","Busambara","Rika","Welamasonga","Mbagilwa","Kisiwani Rural","Olbalbal","Kituvi","Mugeta","Minyanda","Diagwa","Sakasaka","Dr. Mbwambo","Rondo Chiponda","Kurusanga","Makiwaru Cogi","Itende","Kinampanda Ttc","Luilo","Kikolo","Mukulu Elc","Mbeya","Maswa","Bukomela","Bashne","Uganga","Nkololo","Kiswago","Igangidung`u","Tumaini(konayabwiru)","Ufukoni","Mahorosha","Kasang`wa","Gare","Marambeka","Mtii Kanza","Central Uhuru","Wazo","Zajilwa","Zinga Miguruwe","Njianne","Irkiushioibor","Mampanta","Chibula","Makuyu","Muzdalfa Kiwalani","Mungushi Luthera","Ffu","Ihanja","Ukange","Tembomgwaza","Anglican (dvn)","Sokorabolo","Karitu","Mdilu","Bukoko","Chigunga","Chalangwa","Mwabubele","Ushetu","Mus","Kagemu","Eag","Mtindiro","Kingupira","Ikulu","Mtae","Tayma Care","Huduma","Mbarika","Imalinyi","Kawetire","Mbalizi Jwtz","Umati-mbinga","Wazazi Magugu","T.m.c.r","Ng`ambo","Soweto","Saint Consolata","Igayaza","Ikowa","Kitama","Kamata","Lusahunga","Kurui","Kibwe Magereza","Signal","Kambai","Ngiresi","Ncaa","Sua","Shree Hindu","Lwangu","Chela","Rweje","Lalta","Nyamagana","Murray","Tag","Mchukwi Missio","Ngome","Kinamwigulu","Mwitikio","Lugunga","Lesinoni","Duthumi","Badugu","Nyamwilolelwa","Suguti","Kalundwa","Itiso Missio","Kitalo","Mswima","Butiama","Pangani Distric","Mkarango","Kongolo","Mambali","Nditu","Presybterian Seminary","Nyakatera","Ebe","Moivaro","Bugelenga","Stahabu","Ndulungu","Ahamadiyya","Radienya","Nyakato Red Cross","Nyehunge","Namombwe","Ilinininda","Okoani","Kigongoi","Makuka","Arri Tsaayo","Bendera","Mpola","Shimbwe","Tumaini Rc","Idodyandole","Semembela","Labay","Teule (ddh)","Imalaupina","Machui","Tukuyu","Tpdf","Urughu","Itabagumba","Usinga","Ardhi Inst.","Mwaya Rural","Mwiruruma","Muhunga","Tangeni","Kambo","Kikulyungu","Ntobeye","Msolokelo","Naibili Rc","G.e","Nyakabango","Kitogoro","Mwabalatulu","Mbarali Distric","Rumashi","Mwaseni","Chengena","Buhindi","Lolkisale","Chakwale","Igumbilo","Imecc","Msaranga","Mwakipopo","Huruma Cogi","Nyamilama","Ilolompya","Utilili","Msanga","Bwasi Sda","Al-ehsaa","Mnima","Shirimgungani","Tegeruka","Bakwata Chang`ombe","Kipalapala","Lutende","Mtamba","Nyarombo","Gomvu","Baura","Bukwimba","Hatelele","Rwanga Tmc","King`ombe","Igota","Maguliwa","Usanda","Kinyasi","Sayaka","Chihoma","Kigarama","Vikindu","Nangomba","Rugaze","Mpui","Masebe","Forest Dental","Bahi Makulu","Gs","Ibihwa","Nyabange Tmc","Mkombozi","Huruma","Ndundunyikanza","Kisiju Pwani","Libobe","Saibaba","Sabrina","Gumanga","Majohe","Kirare","Kijombe","Sangilwa","Chikopelo Missio","Mwamagigisi","Natta","Songambele","Kintinku","Kenyana","Mitwigu","Nyaruyoba","Nansio","Mitomoni","Anglo Charity","Mungaa","Ukumbisiganga","Nambahu .","Vumari","Sofi Majiji","Kipanga","Kishinda","Mao","Mikindani","Vituli","Doromoni","Patandi","Mgombezi","Magu Mennonite","Kyeeri","Idete","Mamba","St. Camilius","Bwizanduru","Mahaha","Mbopo 	","Kishanje","Ikuini","St. Magdalena","Kayanga Pr","Airport Church","Lerang`wa","Kahe","Liwangula","Madisemo","Vijibweni","Kakuraijo","Rosana","Nyimbili","Kigamboni","Michenga","Kilakala Sec.","Chereche","Isseco","Aic","Bugoji","Rwamlimi","Mwalugulu","Doma","Marumba","Afya Bora","Mwabilanda","Jija","Moravia","Riroda","Trc","Nagaga","Kipatimu","Mayomboni","Lusaka","Tembomgwaza Ilala","Nampemba","Naikesi","Kumhasha","Ngorongo","Consolata","Kiroka Missio","Mbuli","Meserani Juu","Kisogwe","Olkokola Rc","Kyobo","Uparo","Msowero","Hebi Juu","Bakwata Chato","Magoto","Kifone","Nyakabale","Mreno Missio","Mpanga","Tamta Makorora","Kihare","Kagenyi","Pacific","Ilunde","Ntalikwa","Matundasi","Igoma Missio","Loxumanda","Matogoro Kati","Itale","Shingatini Kkk","Ntanga","Airpor","Ccbr","Chinamili","Iyendwe","Mwawaza","Kaoze","Laela","Basodawish","Bugisi","Grumeti","Hembeti","Ndudutawa","Mpara","Nagulo Mwitikila","St. Theresia","Ngulu","Lugeye","Buhemba","A And A","Vip","Bwitengi","Mbuluma","Tunduru","Mbuchi","Nkhoiree","Mungumaji","Mharamba","Kwasunga","Cheleweni","Mahezangulu","Shishani","Nkokoto","Sda","Natiro","Endahargada","Kabuku Ndani","Kinyamshindo","Kiwira Private","Shinyanga/ Mwenge","Kaning`ombe","Dirim","Hanga","Magali","Muslur","Uviwana Fbo","Muheza","Balangdalalu","Mperamumbi","Kowak","Jkt Itende","Lububu","Mbala","Nanyamba","Utimbe","Kakonko Missio","Busegwe Sda","Kikweni","Mlowo","Ndala","Sero","Nakingombe","Ikama","Kibaigwa","Nangunde","Boma Gov","Fulwe","Pangawe Sisal","Amboni","Shume","Makangalawe","Mizanza","Lipwidi","Nyamisivyi","Tlawi Rc","Jojo","Namajani","Mwamishali","Gua","Itetemia","Neema .","Ntwike","Nyampande","Ndono","Ruaruke","Chalinze Rc","Oldadai","Kigadye","Chinyang`huku","Matumaini Crc","Kizinga","Rulenge","Usolanga","Lengasiti","Lahoda","663 Kj","Kitobo","Isongwa","Tumaini Missio","Trcs Market Stree","Makutupora","Marumbo","Tunyenye","Kigwa A","Tumaini Kibaigwa","Songambele Azimio","Mazizini Coh","Meatu Distric","Keni Aleni Rc","Fuizai","Ikunyu","Uhamaka","Kikongo","Mondo","Kiwawa","Raranya","Igunga Chf","Kaengesa","Khubunko","Mpandagani","Kulasi","Ugwachanya","St. Raphael Missio","Ngongowele","Poli","Rwele","Nduguti","Oldonyosambu","Kapeta","Gawaye","Dimba","Bereko","Ndomondo","Mazingara","Ipululu","Luhangai","Nyasaricho","Tayodea","Sae","Jangalo","Levolosi","Mnyambe","Mkula","Mlunduzi","Mtakuja","Mtala","Nyota Ya Bahari","Nkomolo","Nsengony","Mjesani","Village Of Hope","Kintanula","Kigwe","Momela Anapa","Nambahu","Nyahiti","Ipogolo Rc","Iganzo","Nakopi","Suji Gonjanza","Hedaru Rn Nina","Kakanja","St Thomas","Msaje","Nyabumhanda","Chenene","Kyelima","Sheya","Mkiwa","Marumba_nanyumbu","Shirimatunda","Misungwi","Jaja","Lumuma","Manayi","Heri Missio","Lambo","Chamchuzi","Mabira","Nyang`honge","Komsala","Igalula Sikonge","Mtula","Mkungugu","Bujela","Misasi","Namabengo","Bumangi Gv","Bargish","Sinza","Songea Private","Chitunde","Rangwi","Ishenga Shia","Mosongo","Nshara Bakwata","Mkongobaki","Boimanda","Mount Meru Regional","Mwada","Dawasco","Nyaluhande","Mwanduigembe","Wampembe","Mburusa","Sakamu","Banja","Health Care","Masaika","Uhai Baptis","Churuku","Moshono","Kibafuta","Mawenzi","K.m.t Mkolani","Ipunga","Kibengu","Minyembe","Losimingori","Tunduru Ya Leo","Mirongoine","Buguruni","Itamka","Haydom Luthera","Kilomeni Sofe","Ilonga","Kitwechenkura","Babayu","Naberera","Kinyerezi","Mwamalili","Faraja","Muhange","Ntoma","Kipugira","Vigwaza","Kwekivu","Frontline","Banemhi","Matanga","B.o.","Mnyeu","Masawi","Dr. Mziray","Mtunda","Mzimuni","Fort Ikoma","Nkomang`ombe","Kimang`e","Kwambe","St. Raphael","Nyarero","Mwengemshindo","Kinaga","Ruhunga","Kiwangwa","Nsonyanga","Kasitu","Itilima","Ikungulyabashashi","Gwata","Kilolo Rc","Charlote","Wazazi Bukombe","Tukuwa Martenity Home","Nyakahanga","Nkundutsi","Lundo","Kakobe","Gaswa","Tyma Msomeni","Myula","Mwakijembe","Mwawile","Viziwaziwa","Kinampanda","Kaseke","Murongo","Mvumi","Hika","Matala","Msanzi","Arusha Medical","Terra","Migato","Mandaka","Holy Family","Ikula","Songambele Karagwe","Singida Regional","Chakulu","Vianzi","Refugee","Kihurio","Nyamisis","Lusisi","Jk","Ngeme","Mbangala","Mkinga","Kombe","Duga","Kwangahu","Mwangoye","Mkwangulwa","Nzera","Nyabusozi","Igwamadete","Bunyambo","Igima","Airwing 603 Kj","Kolandonto","Kweisapo","Ipuli","Nguni Luthera","Nyabugera","Nyegezi Fisheries","Mkuka","Rwambaizi","Kibungo Juu","Mbaghai","Mwambola","Kambarage","St. Otto","Kingale","Utimule","Usa River Gv","Msanyila","Igaka","Malwilo","Masurura","Sakawa","Mwakaluba","Chilulumo","St.raphael","Kwatwanga","Mbuyuni","Komolo","Mlilingwa","Mtego Wa Noti","Gidahababieg","Kilangala","Mivanga","Sagata","Ncheli","Sagara","Loborsoit A","Kurugee","Meka","Kikomero","Nahoro","Bukiliro","Ilemba","Nyambono","Nyangalamila","Mlungu","Mtenkente","Magereza Urambo","Nyanguge","Mwarusembe","Ilumba","Kisinga","Ikungi","Arafa Majumba Sita","Itiso Gov","G Lambo","Kiuta","Kabila","Tulieni","Mtyalambuko","Mabwepande	","Mlembwe","Dosidosi","Lupanga","Kazingati","Mbuya","Mengwe Juu","Mkiza","Idihani","Ikonya","Kihondo","Mongoroma","Mihingo","Sangambi","Mwese","Kirongaya","Mnolela","Nyasato","Namdc","Magoma","Mtonya","Chikoweti","Makongolosi","Kilanga","Masinki","Tanesco Kihanzi","Anne Kiilu (mdawi)","Mahukuru Nakawale","Dr. K.k. Kha","Mautya","Kapalamsenga","Kikukuru","Idahina","Kaloleni","Ilambo","Chita Rural","Noondoto","Msonge","Orkesume","Narungombe","Mchukwi","Mkundiamani","Ngomeni","Sandali","Mvuha Missio","Chamaguha","Kibedya","Iwela","Idiga","Halungu","Nkundi","Chagu","Kasapo","Malolo Missio","Mhongozi","Zacc","Keko","Izinga","Narakawo","Busenda","Iringa Regional","Maroroni","King`ang`a","Omega Musoma","Kwatango","Kidodoma","Ipinde","Karatu Lutheran Dd","Balatogwa","Cada Tarime","Bagamoyo","Matema","Sakura Est.","Kiechuru","Bwambo","Mlandizi","Madingo","Arri","Kagondo","Mabibo	","Kimnyaki Maasai","Ntumbati","Mpilipili","Nsunga","Police","Tongoni","St Ernes","Chemchem Rc","Kamuli","Malambo","Choma","Kwakoa","Lumbila","Apostles","Ngunichile","Izigo","Tutuo","Ikhanoda","Nakiu","Wenda","St. Vince","Mchuluka","Fumvuhu","Msandaka","Bushiri","Rungwe Mpya","Lole","Ngole","Lusitu","Oldonyosapuku","Magawa","Ngulugulu","Mico Mwembesongo","Lengijave","Zogolo","Mianzini","Itela","Nyenge","Ruangwa Distric","Nzaga","Nyabinda","Chibumagwa","Kalangalala","Sawala","Mwasenkwa","Mwabuzo","Masanwa","Mwaya","Namhula","Mlale","Mtakanini","Liula","Igongwa","Baraa Rc","Mtama","Magu","Ngingama","Usagara Sec.","Mwanga","Jema","Kilinga","Kasala","Tamau","Bakwata Mbika","Kafunzo","Kagera Regional","Tmc Mkuyuni","Isandula A","Luponde","Uhambila","Ntepeche","Tanzania Methodis","Burigi","Chikunja","Makasuku","Gisa","Ruanda Ii","Kwakombo","Shevick Cogi","Limamu","Mkowe","Makangara","Nyantimba","Mafinga J.k.","Katunguru","Magoda","Mirui","Kizumbi Jwtz","Linoha","Ngyeku","Ulenje","Bumbire","Isoliwaya","Bokomnemela","Chunyu Mpwapwa","Mwabagalu","Kimwanya","C. F.","Nyabionza","Chekereni Rc","S.t Anna Huduma","Nkanga","Kanyenye","Uhekule","Katani","Uwanza","Lepurko","Bugarika","Oljoro","Sopa","Kikombwe","Mohs","Kidegembye","Bupu","Mteke","Isagenhe","Ihugi","Mndimbo","Mgwashi Rural","Mahuninga","Lwenzera","St. Francis Rc","Kidahwe","M/chai","Musati","Mbezi 	","Kikwawila","Bakwata","Tulo","Kaselya","Kang`ata","Nyamanga","Itaka","Muze","Makambako Rc","Hale Private","Salaama","Mnongodi","Weruweru","Muungano-chamwino","Biharamulo","Murufiti","Uswaa","Maili Moja","Imiliwaha","Mamire","Munzeze","Mwarongo","Ikondo","Mayo","Image No 8","Mico","Chekeni Mwasonga","Korogwe Ttc","Mwandusi","Kasongati","Buigiri","Tanga Sec. School","Mgera","Itope","Tabata A","Arapha","Lusala","Mlola Rural","Makazi","Sanya Juu","Bukima","Ijinga","St. Gaspar","Tingatinga","Utambalila","Mwabusalu","Mugoma Health Service","Mto Wa Mbu Rc","Boma Crt A","Iwafi","Matipwili","Mbinga","Chala","Kaminula","Mvure/kongei","Lusanga","Murgwanza","Bunduki","Rau","Maskati Missio","Kwangwa","Mikangaula","Ijuganyondo","Chemchem","Matombo Missio","Nyakatuntu","Ruaha","Mkoha","Uchindile","Magambua","Sda Tarime","Chikobe","Likombe","Moce","Shirati","Icmf","Kasandalala","Kabiga","Mlilayoyo","Ilogombe","Manzase","Njoge","Seregete B","Sangasanga","Lilondo","St.bernad","Nyakisasa","Mwajiji","Kiloleri","Changa","Masike","Ulete","Njinjo","Mwankulwe","Chamalendi","Biro","Maskati","Lipalwe","Usinge","Sunuka","Visaraka","Cheyo","Bumba","Nyamahanga","Nyarubanda","Tae","Shinji","Ishozi","Mikongo","Inzomvu","Nkaiti","Mkusi","Vyeru","Kisereni Luthera","Mwamapalala Rc","Mbande","Kwai/st. Catherine","Songwa","Kalenga","Serengeti","Njoro","Idende","Glory","Ngala","Shila","Isoko","Mangaka Distric","Mvinza","Genkuru","Lula","Ilalangulu","Motto","Mbega","Bubombi","SDA","Mbete","Mwagala","Bupandagila Sda","Bugorora","Tohs","Bukwali","Hale Tanesco","Alpha Maternity H","Mapogoro","Luvuyo","Mvuleni","Nyegezi","Haubi Va","Mwanamonga","Manga Musoma","Nyamikoma","Luwa","Ugabwa","Nronga","Kweikonje","Manundu","Chingulungulu","Namchinka","Msosa","Ngonga","Madadi","Vituka Tayma","Lyamungo Rural","Upendo Crc","Baptis","Kideleko","Nkalankala","Ntulya","Manoleo","Mkata Ranch","Isongole","Kipumbwi","H H Aghakha","Bukiriguru","Ihomasa","Kisemu","Liganga","Kirwa Mashati","Royal","Nyamhongolo","Waranga","Isunura","Rwigembe","Ifunda Rc","Makingi","Rwambwere","Kavifuti","Izyira","Buyuni","Matemela","Kicheba","Ntunduwaro","Kiegea","Likuyu Mandela","Luhangarasi","Mchichira","Miguruwe","Medicare","Nyamongo","Isele","Mula","Msae Kinyamvuo","Isolo","Lushamba","Wayda","Kishisha Rc","Uomboni","Lufilyo","Gitting","Rangitatu","Mateves","Vikuge","Busaka","Nzubuka","Mkutimango","Mkoka","Oltrume","Kisana","Njirii","Kipengere Fbo","Dawar","Namindondi","Mgagao","Panyakoo","Marangu Hq","Ihekule","Mavurunza","Bwanga","Malagarasi","Msale","Uliwa","Mtombozi Missio","Kiponzelo","Uluguru","Savana","Kwafungo","St. Veronica","Kabuku","Nyaminywili","Kigondo","Makungu","Kimala","Toangoma","Majimoto","Kibanga","Igawa","Rehema Rural","Nyachenda","Nghumbi","Kwembe 	","Endallah","Moipo","Mpugha","Nyahongo","Msomeni","St. Joseph","Suma","Yombo Ufundi","Karukekere","Tarime Distric","Tanwa","Chikola","Mtepeche","Katundu","Kapozwa","Frelimo","Kigoma Interntnal","Bombo Luthera","Milingano","Maliwa","Kitanga","Mwibagi","Kaole","Kwamatuku","Tumaini Geita","Dongo","Jipe","Lipome","Nkowe","Dulamu","Kitendeni","Pahi Altc","Mbegani","Kimbe","Mwaisela","Nancy","Biharu","Ndala Missio","Katumba","Isma","Sabodo","Dutumi","Amani Makolo","Ikasi","Nangwa","Mikumbi","Magiri","Mwabalebi","Tulawaka","Kizara","Leganga","Ikuna","Mgala","Wazazi Lyambamgongo","Nyasirori","Dr. Maduhu","Iyenge","Mkenge Mkuranga","Mdimba","Nyijundu","Wikichi","Muzidalfah","Bwiko","Kamugendi","Mafuruto","Igalukilo","International School","Ponde","Mapera","Sitalike","Kishogo","Mbahe","Makutupora Jk","Winza","Shelui","Tandahimba Distric","Mitole","Hanu","Msange Missio","Magubike","Gwandi","Luhundwa","Bujonde","Kilondo","Uyui","Mputa","Mlanda","Chikande Mat Home","Namiyonga","Sadani","Magadu","Ekenywa","Menonite","Bangata Luthera","Mjawa","Jogolo","Narasero","Nyamatare","Kwamndulu","Tabora B","Nyakinyo","Masatu","Rudewa","Muyovozi","Matembwe Fbo","Rushwa","Mashati Red Cross","Butainamwa","Ludewa K","Nyanza Salt Mines","Ngudu","Baleni","Kisaba","Chinangali I","Ufana","Luteba","Ilkilevi","Leguruki Luthera","Namgogoli","Ndole","Mlowa Bwawani Missio","Ngaya","Engikare","Ishenga","Kikhonda","St. Michael","Kumubuga","Mwenge	","Kisorya","Kakubilo","Gwitiryo 2000","Endamilay","Iponya","Macjays","Ndunguru","Mtua","Kunzugu","Mvuti","Ibindo","Sisi Kwa Sisi","Ilela","Nyankanga","Longoi","Mihumo","Ibiri","Mico Kihonda","Ilobashi","Wami Vijana","St. Mary","Nyantakara","Nanjaru","Makhoromba","Melela","Manyire","Potwe","Jabal Hira","Isseke","Ifupa","Msolwa Statio","Samarita","Kisosora","Lupiro Rural","Nyakavangala","St. Immaculate-miyuji","Killidu I","Ipole","Mugunzu","Arafa Mafia","Tarime Goodwill F","Endagwe","Saza","Kwizu","Bwakira Chini","Ilalabwe","Mwisenge","Tloma","Burito","Ilundo","Mgongo","Utegi","Bp Swaminaraya","Cm","Kizuiani","Kianda Igonda","Pamoja Calvary","Endasak Pentecos","Ndanda","Jaribu Mpaka","Ishinsi","Kamara","Kifuleta","Kashishi","Nyandira","Qurus","Tamota","Mshewe","Kainam","Kasharazi","Mwadubi","Njugilo","Ugenza","Ibutamisuzi","Kamnyazya","Kitandililo","Rwang`enyi","Busongo","Ikindilo","Suni","Migungumalo","Maluga","Murubanga","Nsalaga","Majahida Aic","Kirongo Rc","Mwabayanda","Sengerema D.d","Mekomariro","Iramba","Namupa","Namtumbo","Kashanga","Mwashiku","St. Norber","Msagara","Iwowo","Mihima","Chienjere","Traffic","Usevya","St. Benedic","Kilulu Duga","Chalowe","Mwai","Managha","Toghotwe","Mpogolo","Mikese Gov","Korogwe Distric","Isengwa","Puma","Gonja Maore","Bushingwamhala","Nyabilezi","Kibande","S.d.a","Nurraifo Yombo Vituka","Kilindinda","Mapinga","Kaguruka","Mt Mpopera","Gibaso","Tundwi Saongani","Mwigiro","Nyamwage","Dr. Attman Cd","Manushi","Lusane","Good Samarita","Kiduduye","Ndembezi","Lukala","Mbugayabanhya","Arusha Wome","Mawella","Mandakerengs","Lagana","Endakiso","Liwumbu","Sakalilo","Berega","Mkindo","Kisangi","Bankolo","Manda","Olmolog","Kikole","Wanyere","Ilemera","Kiagata","Ndevelwa","Buguruni Mart Ltd","Nduru","Mahango Mswiswi","Nyakanazi","Dimon Private","Ruhembe","Nyabisaga","Mabatini Police Line","Kanyezi","Mkiu","Msongozi","Qash","Mtende","Engusero","Mpilani","Makonge","Lilimalyao","Bulongwa","Namichiga","Matare","A.i.c.t Kimkumaka","Nadosoito","Ibugule","Msimba","Ngasamo","Itaba","Kibong`oto Tb","Juva","Lukarasi","Magereza Uyui","Qaru","Sanza","Wazazi Tinde","Kiabakari Magereza","Mwanabwito","Paji","Ihowanja","Halawa","Kibumba","Lamaiti","Ikunguigazi","Kilombero","Vunta Rural","Lubanga","Msongozi Missio","Kagera Sugar","Mwabowo","Mwembesongo","Mwamlapa","Bombo","Jimbo","Maneromango","Joseph Medical Care","Kisiki Rural","Mbekenyera","Lupingu","Ukune","Benbella","Kambala","Kmcl","Mchombe","Kwaluguru","Kindi","Makorongo","Binga","Nyamatongo","Katoro Tcmc","Bamita","Mazinge","Sangamwalugesha","Busisi","Kizwite","Victoria Clinic","Salale","Mkombo","Uwemba","Madege","Kichonda","Mawindi","Katikati","Nyakasungwa","Kinazi","Mlalo","Lamadi Mennonaite","Nyumbigwa","Mabokweni","Tmc","Nondwa","Msingi","Ibondo","Kkk","Ngongongare","Muhanga","Chitemo","Msamba I","Loya","Mtonga","Matumbulu","Shinyanga Regional","Ismani Lwan`ga","Izava","Mtimbwilimbwi","Gumba","Lwamgasa","Chanzuru","Kasanga","Kifaru","Chumbageni Priso","Ihimbo","Ithnaasher","Bukwe","Nyamikoma Kwimba","Kikulura","T","Nurraifo Tungi","Iyogwe","Haneti Rch","Kizi Mpwapwa","Twendembele","Hedaru Cogi","Nkwimbili","Nyangamara","Makangarawe","Samvura","Mkulazi Kkk","Kibondo","Makale","Idibo","Mwanangi","Maziwa","Butu","Nyashimbi","Santilya","Pole","Nyandekwa","Monduli","Uyole","Makowo","Komfo","Mabwerebwere","Muruvyagira","Mtera Tanesco","Iguguno Rc","Magereza Gov","Mnyawi","Unyamikumbi","Tanga Diary","Daluni","Msanga Mkuu","Mpona","Msesule","Tindigani","Shongo","Mgila","Sigunga","Pasiansi","Mkambalani","Luegu","Nyarugusu Refugee","Mitundu Rc","Kazilankanda","Byeju","Tawi","Dirma","Kilosa Mufindi","Mishepo","Nyamainza","Ndelema","Busumba","Kitomondo","Bwiru Boys","Kilasilo","Mbeya Regional","Bara","Mnkola","Kilimawe","Seleli","Buseresere","Somanga","Mbushi","Migsegsa","Mea Mtwaro","Matale","Mnenia","Umoja Nurraifo","Kasamwa Sda","Mwamjulila","Iglansoni","Ubinga","Heru Juu","Chandamali Jwtz","Mnekezi","Di","Nkutuisenga","Sfddh","Bio Health","Kipeta","Kalilankulukulu","Pasua","Makendza","Mwananchi","Kasanda","Sange","Mkoyo","Kirushya","Baraki","Mbebe","Arusha School","Holili","Lemira Luthera","Kakola","Nyambunda","Sumbawanga Regional","Mtyangimbole","Kilolo Gv","Ndenyende","Kalengakero","Busungu","Mwajilunga","Mishamo","Mpasa","Image","Canosa","Tosamaganga Dd","Thawi","Utunge","Ipande","Mohoro","Kisanga","Tungamalenga Luth","Mwera Mpya","Lundumato","Shed","Mponde Vuga","Mwasubuya","Buziku","Usoke","Levishi","Mpora","Mtunduru","Maguu","Kiongozi","Bugulula","Moa","Mtwango","Walter","Rwelu","Kiwege","Bulombora","Kmt Buseresere","Kalebela","Mbosho Rc","Nsabayo","Nyambula","Isanzu","Lengatei","Kagunguli","K.v. Missio","Kilangali","Ruzinga","Rc Kondoa","Ifupila","Handeni Distric","Nyumbanitu","Malkia Wa Ulimwengu","Hydom Luthera","Pangaboi","Ng’ombo","Hekalungu","Sokoine","Nzuguni","Nanyindwa","Taso X-ray Centre","Usokami","Nalasi","Mingumbi","Ngofila","Mkuranga","Komuge","Mikoni","Mkata Kijijini","Ibaya","Mbawala","Langoni","Chiboli","Tackford","Hekima ( Bit)","Ngorika","Jibu Letu","First Health Kil.","Kongowe Fores","Kate","Al-amar","Darajani Lcc","Cogi Kaya No.1","Kitulila","Mbalibali","Mbeya Referral","Mpale","Sakina Jwtz","Itete Gov","Kinyeto","Nyarulama","Majengo Dom","Mpete","Kwamsisi","Moita Kiloriti","Kibosho Barazani","Kifufu","Ikuwo","Marie Stopes","Lugombo","Namihu","Mjele","Magati","Miganga","Kili Nursing Home","Magengati","Mtowisa","Mbondo","Kolo","Sauti","Mzalau","Kachamba","Matumbo","Ng`walida","Shitage","Asanje","Chipuputa","Kwinji","Dare","Sikh Temple","Malela","Lumesule","Ngarenairobi","Mambwenkoswe","Wotta","Kidogobasi","Bonyokwa","Itunundu","St Joseph","Maramba Jk","Sadani Rc","Luse","Kirya","Ivuga","Mandi","Igigwa","Kleruu","Amani- Nimr","Mafuriko","Nguge","Mzenga","Dareda","Mnacho","Mayuya","Kanyonza","Ics Buguruni","Kivungu","Mbuga Nyekundu","Ng`ang`ange","Tamta Str. 1","Nyabange Gov","Nainokanoka","Kindimba Chini","Tentula","Mbwewe","Magereza Mkoa","Bassotu","Kirangare Rc","Imani","Mbooga","Mhunze","Majeleko","Mico Kasorobo","Mwamigongwa","Old Maswa Rc","Ngongwa","Milala","Lubanda","Tangeni Missio","Kipule","Tsawa","Likuyuseka","Kiloleli Juu","Mbori","Moshi X-ray","Kwamsanja","Gm Itezi","Mkwiti","Nyakarilo","Mwamalulu","Kwiro","Ntobo A","Utanziwa","Olmo","Kisangara","Kizumbi","Busekeseke","Mabula","Zanka","Mikoma","Sda Urambo","Chambala","Mkanyageni","Magereza Mahabusu","Likamba","Ujiji","Aicc","Mvugwe","Urambo","Bulungwa","Jaffery Charritable","Itenka Moravia","Magila","Kijilishi","Tanangozi","Namasakata","Burere","Matovolwa","Lyasa Mfukulembe","Magereza Bariadi","Kauzeni","Misima","Mwakizega","Iringo","Kitongo","Kiliwi","Uhambingeto","Seeke","Salawe","Gulwe","Mgolole","Mongwe","Morogoro-songea","Kidugalo Wazazi","Magoroto","Mwamanyuda","Lukange Missio","Kamel","Omukalilo","Hasamba","Denish","Nakatunguru Sda","Kwikuba","Mkongotema","Kiromba","Igogo","Mgandu","Imalilo Songwe","Namisangi","Kizuka Jwtz","Mpalanga","Lily`s","Giorgio Frasst Rc","Chanika Karage","Langiro","Katundulu","Mnali .","Kigala","Makwale","Itensa","Murangi","Km","Nyakagwe","Mipa","Ibumi","Nindo","Fufu","Namangale","Mbwara","Ibamba","Nambilanje","Ilebelebe","Nyakaduha","Kyasha","Kibungo Chini Missio","Kagunga","Senga Geita","Mahembe","Kalaela","Kikunja","Gendagenda","Dream Care","Misigiri","Mumbaka","Murutilima","Ntendo","Wasso Dd","Utete","Bugisha","Tera","Aviatio","Aljamih","Tyeme Elc","Kabare","Bupandwamhela","Gonga","Kindwitwi","Ihembe","Uchunga","Nyakimue","Mkamba","Wiliko","Sakwe","Muccobs","Chinangali Ii","Ruvuma Regional","Msinune","Lengo","Liwale B","Mwaguguli","Ndurugumi","Kisungamile","Kirumba","Vikula","Mbingu Sister","Tewe","Igombe","Talawanda","Mico Kiburugwa","Ndambwe","Kikombomaduma","Ronsoti","Isenga","Kisiwani","Songosango","Namkukwe","Ufuluma","Makanya Kitivo","Nandembo","Manchimweru","Pamila","Gunge","Mwang`honoli","Kilelema","Masage","Matai Va","Mollo","Uhambule","Kung`ombe","Nyang`olo","Vuga","Kiongoroni","Mtunguru","Medical Research(nimr","Mikonko","Bukemba","Lupande","Ocean Road","Dutwa Aic","Buhama","Bweri","Mwamalole","Mbingu","Zalu","Gombe National Park","Mkonona","Itunduru","Lobosire","Mgeta Missio","Nyamato","Kalya","Arafa Mapinduzi","Nyarugunga","Chahwa","Itaga","Kizumbi Muccobs","Kataraiya","Mngeta","Hekima","Katare","Mwandiga","Chiwanda","Chimpumpu","Shinyanga Sec","Myangayanga","Rusimbi","Nyanganga","Kirima","Masoko","Ntenga","202 Kj Faru","Kamsamba","Kweditilibe","Jamaa Chartable","Bakwata Ulyankulu","Karibu","Morogoro Regional","Sisimba Dental","Mwibuye","Mwamakalanga","Huduma-kilimahewa","Utuja","Nkhome","Segera","Engarenaibor","Kasyabone","Maundo","Miteja","Nyasho","Endasak","Nyanyembe","Old Shinyanga","Ussongo Rc","Lutindi Mental","Ligera","Wino","Makoka R.c","Miono","Makole","Avinajo Umoja","Kilangawana","Ligumbiro","Wazazi Kabuhima","Mafyeko","Izumacheli","Makao","Keza","Lendikinya","Ihumwa Jk","Buger","Ame","Matimira","Kisarawe Ii","Bakwata Masumbwe","Makiungu","Bwambo Rc","Chissano","Bulekela","Mpombwe","Kikonge","Ruhanga","Kisesa","Kangariani","Referral s","Buganika","Kalalani","Jwtz","Ikongolo","Mikuva","Sikonge","Mwasinasi","Kichangani Moro","Kishapu","Kasahunga","Lugulu","Bulamba","Bwai","Shilela","Bulembo","Idete Priso","Kyota","Msima Sayuni Fbo","Mughunga","Luchelegwa","Ikengeza","Igamba","Maparoni","Tingi","Kijiyombo","Kihongo","Mang`ola Priso","Changarawe","Gamasara","Usunga","Kigonsera","Lupembe","Ukami","Mukunu","Misyaje","Kighare","Chigongwe","Kidaru","St Getrude","Kising`a Isma","Uma Ti","Makonde","Mtila","Kiwalamo","Iporoto","Mbutu","Mati Uyole","Haleluya","Nsenga","Cogi Unga Ltd","Bunda","Ugowola","Kitasengwa","Nkwenda","Kambasegela","Bitimanyanga","Ibaga","Tongoni Jwtz","Njelela","Mang`onyi","Mkuti","Kerege","Kisesa `b`","Kisongo Pig","Mgazini","Mzinga","Mbono","Iringa Mvumi","Magereza","Makande","Valeska","Gibeshi","Moyofuke","Ileya","Disunyara","Kelema","Kia Rc","Enduime","Usagala","Buhororo","Narunyu","Kibaoni","Makombe","Marie Stopes Private","Univ Of Dodoma","Mazimbu","Kigombe","Olilokola","Ngeta","Sifika","Bujugo","Geserya","Ufipa","Kangeme","Nyungwa","Kigoma","Lumuma Rc","Arkata","Itimbo","Kilomeni Rc","Ozzane","Ilagala","Farkwa","Nkiniziwa","Mlebe","Samaria","Bomalang`ombe","Hai Distric","Ardhi Institute","Sepukila","Mwembe","Lyenje","Ijava","Ffu Nshambya","Corner","Mahurunga","Madaganya","Homboza","Rigicha","Kilema Kisao","Kagongo","Masela","Victoria-kilimahewa","Mwanyumba","Lundi","Kitulo","Nzugilo","Gereza La Ruanda","Mandera","Bunegezi","Arafa Ugweno","Aljumaa Chartable","Endabeg","Elwai","Buyekera Police Line","Ukiriguru","Kwedizinga","St Theresian Sis","Chibuji","Nyasa","Lufumbu","Dr. Attman Karema","Bwipa","Kwamtoro Rhc","Lubungo","Karatu Rc","Sikonge Rc","Nyasaka","Ngalimila","Matendo","Maisome","Bukombe Distric","Bugalama","Likwachu","Mtumbya","Nyamutelela","Rehemtulla","Mpitimbi","Isapulano","Mswaki","Utende","Kingugi","Nainokwe","Milola","Kiwambo","Mtoa","Mbaramo","Mwisole","Mkolowony","Kitopeni","Ruvu Jk","Mpepai","Kabondo","Mbola","Nyatanga","Mafia Distric","Kongolo Mswiswi","Nyamakuyu","Bujuruga","Mwankoko","Isangati","Ruganzo","Ngogwa","Paradiso","Msamba","Kibigiri","Bashay Rc","Msimbati","Kebweye","Ndogowe","Magwamila","Tegetero Missio","Samuye","Bwisya","Gehandu","Endashangwe","Liti Tengeru","Mtonya Namtumbo","St. Clara","Marera","Sayusayu","Saranda","Al-mohlem","Mvimwa","Pongwe","Uzia","Lyele","Usa Rc","Itowo","Ruvu Kombo","Msamvu","Lukokoda","Kitelela","Miombo","Isange","Bakwata No 1","Banawanu","Wilunze","Nyakayenzi","Izuo","Chumo","Dung`unyi","Namapwia","Bakwata Mgeleka","Malaja","Kiwe","Kasema","Kichemchem","Chawi","Magunga","Ivuna","Mugera","Huruma Kanora","Malolo Mpwapwa","Mangopachanne","Kwankonje","Mkombole","Buhembe","Ngage","Katwe","Ng`homango","Mlondwe","Matandu","Nyamwaga","Kilewani","Kaunda","Nyamilanda","Nsongwi Juu","Kabale","Unango","Mambo","Kisiriri","Sombetini","Lupalila","Bukondo","Itaswi","Isaka Kkk","Igoko","Loborsoit B","Saruji","Mtimbwani","Imalampaka","Kasumo","Nangoo-tulizo","Muhukuru Nakalawe","Ifunda Gov","Kirando Rupp","Luduga","Machochwe","Ntobo","Naisinyai","Ngimu","Mwendapole","Nyanchenche","Mlangali","Isipii","Bakwata A Majengo","Azimio","Naibili","Mbatamila","Kihumulo","Benjamin Mkapa Distric","Maria De Mattias","Mshikamano","Maneromango Kkk","Seth Benjami","Shree Hindu Mandal","Kihanga","Chamazi","Matwiga","Kawawa","Litembo","Kahama/nhalanga","Endamarariek","Kibamba 	","Kiangara","Nyalwelwe","Mission Mbagala","Mndeme Ndugu","Sanzawa","Shunga","Hurui","Mbongo","Manow","Bhoke","Umoja Gov","Mokala","Buhangija","Kasansa","Wami Mbigiri","Ubwari","Itulahumba","Kipingu","Epiphany","Maweni Priso","Nanguruwe","Sauti (nyegezi)","Makalamo","Wazazi Usanda","Bomang`ombe H/care","Gungu","Kiganamo","Makongondera","Nyabugombe","Mbulu","Crc","Iwa","Usanganya","Madindo","Kigogo 	","Udumka","Fores","Karumo","Mary Land","Afya","Chifule","Subira Kati","Ilalanguru","Msisima","Sanjaranda","Kwamdolwa","Ipala Gov","Mbangamao","Kinesi","Kamwanga","Malipula","Ikungu","Kipunguni","Simbay","Mamongolo","Nyang`ombe","Makorora","Kinole","Chololo","Rungemba","Kagongwa","Kabusungu","Tanznia Breweries Ltd","Aar","Bukangara","Nyingwa","Uleling`ombe","Buhare","Mkotokuyana","Hindu Unio","Hezya","Kiluvya","F.f.u","Emboree","Ilala Simba","Iparamasa","Igogwe","Kirumi","Nyanza","Rao","Kibiboni","Kapughi","Msaginya","Mtumbatu","Katazi","Parane Sda","Kihesa Mgagao","Miswe","514kj Jwtz","Almahadi","Maduma","Magereza Ukonga","Bung`wangoko","Budushi","Ichemba","Igusule","Baraka","Chiungutwa","Mbamba Bay","Ulemo","Itumba","Wanging`ombe","Mgaraganza","Daudi","Kilolambwani","Kilida","Sanje","Chomvu","Sangabuye","Muriti","Namikulo","Kasaka","Chekelei","Mbuba","Kilwa","Afya Cafe","Walemavu","Red Cross","Magereza .","Ngarambe","Chipapa","Nsambya","Kasozibakaya","Mgori","Igalula","Mico Klm Millenium","Etaro Jwtz","Mwamboku","Mengwe Chini","Ilemela Chato","Luwumbu","Kasenyi","Mng`aru","Kabwe","Lipangala","Songe","Mirwa","Kalela","St Michael","Ikozi","Lipumba","Buhunda","Wazazi Lugulu","Nyamatoke","Unyambwa","Msogezi","Namkongo","Karaseco","Mifulu","Bandari","Nanga","Ntumbi","Isanzu Elc","Nassa","Kijima","Ikwiriri","Lubiri","Oloipiri","Chisegu","Luhalanyonga","Itonya","Mkangawalo","Mtupale","Wariku","Arafa Ikwiriri","Luatala","Kisiju","Masasi","Kihuwe","Masange","Zashe","Suli","Kanazi","Themi","Muyuyu","Mingela","Idukilo","Weru","Seke Bugoro","T.t.c.l.","Mafisa","Ngima","Ruvu Secondary","Mapinduzi","Choda","Miguwa","Mahando","Arafa Jaribu","Sulemanji","Kaibanja","Imalamihayo","Mvumi Mission Ddh","Wiyenzele","Likunja","Dr Chikumba","Itigi","Mkongoro","Kilolo","Munguli","Mirambo Baracks","Golden Pride","Mwandoya","Gidamilanda","Makanya Sda","Kolero","Dihinda","Irene Kilimahewa","Moka","Mbuza","Bakwata Tinde","Nyamasanda","Pwaga","Kia Gov","Usangi Distric","Papri","Madunga","Makanda","Kilangajuu","Kipili","Mikunya","Mavuji","Mlali Missio","Liweta","Muungano/zomba","Matekela","Kalola","Malemve","Lubili","Mkwese","Rubya","Kwachaga","Sonu Cogi","Tanica","Igombe 60","Seluka","Reko","Saawe","Songoa","Gambosi","Kalema","Chiola","Magindu","Kisongo Sda","Uzima","Nkwansira Rural","Zongomela","Soya","Uroki","Ngulilo","Mwakitolyo","Mtombozi Governme","Kipok","Kihangimahuka","Rusoli","Kawe 	","Msolwa G. Reserve","Namkomolela","Rungwe Missio","Nkoaranga Luthera","Blessed","Nambendo","Ikoga Mpya","Madangwa","Mazinde","Kimalamisale","Mwanekeyi","Ihunwa Gov","Makote","Dabalo","Ndolage","Luhungo","Nyanchabakenye","Igokelo","Kijungu","Kibuyuni","Kwaraguru","Maramba","Mgunga","Mkwaja","Nyange","Kibosho","Swaya","Kirokomu","Mangombya","Masakta","Katete Chato","Kihulila","Eletra Bosco","Kitwiru","Munyeti","Lifeline","Kwadundwa","Soitsambu","Twasalie","Goha","Kisongo Lcc","Kmt-mganza","Msinji","Coptic","Wazazi Lulembela","Mukigo","St Longino","Nakahuga","Nyagwijima","Uhasibu","Mbigili","Laiseri","Kinambo","Rungungu","Nvtc","Mpolo","Ikolongo","Moivaro Arusha","Luxamanda","Kitua","Kkkt Same","Bungulwa","Dr. Waeli Malisa","Ngerengere","Immaculata Rc","Idunda","Kambi Simba","Mwanza","Kishoju","Mendo","Lunguya","Msoni","Itanana","Kododo","Mpindimbi","Mubunda","Ntambwe","Mkuzi","Sokoine Barracks","Kutani","Bwagamoyo","M.m.h","Uzima Centre","Kilembo","Igwachanya","Chosi A","Chidya","Mtc","Nyumba Ya Mungu Par","Nyansha Life Cycle","Ilonga Ttc","Kisaki","Ukombozi","Matetereka","Kyawazaru","St Fransic Aveier","Mwashegeshi","Sirop","Mnazi Mmoja","Mdwema","Mseta","Usesula","Igodivaha","Naujombo","Majala","Kiluvya	","Segese","Kigongomawe","Idegenda","Nkonkwa","Kikavu Chini","Tatanda","Misake","Maweni Regional","Kitanda Namtumbo","Kabingo","Kajunguti","Ushirombo","Ikumbilo","Chongoleani","St. Aloyce","Burengo","Gunyoda","Burhani Str. 4","Aman Piece","Ngomai","Ndanga","Tegeta 	","Magereza Chuo","Tayma","Ngunja","Ligunga","Yaeda Chini","Masama Luthera","Mtimbo","Kintandaa","Titye","Mukarazi","Mwanhale","Sabato","Kichangani","Lupepo","Namikupa","Arafa Furaha","Mazombe","Kmt Buswelu(wanze)","Tubugwe","Sungu","Ngabolo","Busangu/mugu","Ilula","Red Cross Dental","Mareu","Makatani","Ntuchi","Iwindi","Mwitambu","Karangai Cogi","Kiungu","Luhombero","Gumbiro","Ubena Estate","Tcc","Kashenye","Buhekela","Ngahokora","Mnokola","Mkuyu","Vingunguti","Kodepa","Longalambogo","Kasangezi","Ebenaza","Kala","Pangani Falls","Mkoreha","Dinembo","Mzogole","Kajana","Simiike","Rugunga","Kikunde","Secheda","Mdimni","Lowa","Buhingu","Ilongero","Usengelindete","Okoa","Ketaketa","Ikokoto","Bondeni","Mbugulaji","Kwamkoro","Kinda","Mmoh","Kange","Ndobo","Lukani","Mount Meru","Gezaulole","Mwalala","Saadani","Vumilia","Kiwalani","L.junior Seminary","Amani-chogo","Masimeli","Bugogo","Businde","Pingalame","Uzena","Idc","Olchorovosi","Kilimasela","Mwasingu","Madanga","Jioneemwenyewe","Alamano","Mwageni","Nzigala","Kigonigoni","Chinula","Sholom (st.road)","Milonji","Mahoma Mukulu","Ngombo","Kigalye","Kazovu","Sumbigu","Makuburi 	","Kam Medical","Kitalalo","Muriba","Kinjumbi","Kyaka","Sepuka","Itega","Olkung`wado Momela","Rushungi","Towero","Ottu","Migoli","Igawisenga","Kimamba","Lembeli","Ihemi","Hangangadinda","Mabama","Kwamba","Kimelembe","Majengo Gov","Muyenzi","Hruma","Fame","Sofi Missio","Milyungu","Msimba/ Mikumi","Ugogoni","Kashasha","Mzumbe Idm","Ngorotwa","Ndapo","Kilando","Mkindi","Mazinga","Ipogolo","Usure","Makere","Ngamu","Lumecha","Hinju","Madope","Oldonyowasi","Goima Va","Sabilo","Kalangali","Mpamantwa","Gambajiga","Mavala","Agakha","Elct Ilemera","Sykes","Msambara","Mkenda","Madimba","Kasuga","Mashule","Ifwenkenya","Cadak","Tulya Elc","Js Bahbra	","Kmsd","Mfyome","Rusesa","Zirai","Kingole","Mpunguzi","Umbwe","Kangagai Rural","Mlamleni","Kabanga Private","Mkima","Msanda Muungano","Mwanjolo","Kiwira Coal Mine","Zaharau","St. Francis Kwamkono","Mipingo","Isansa","Chunya Distric","Igumo","Utalingolo","Ivalalila","Makurunge","Tarangire","Living Stone","Mundindi","Nsogolo","Cada Rorya","Humekwa","Wasa Rc","Makangwa","Ikongosi","Khusho","St. Mary`s","Zombo","Gusuhi","Gemai","Kisumba","Baraka Cheyo","Djuruligwa","Kizi","Mugango Km","Mtondo","Lutona","Buhanda","Mpafu","Ngaseke","Tuwaenzi Wazee","Bulongwa L.","Unyangala","Kichananga","Nanjota","Ruvu Statio","Kigongo","Meatu","Saneno","Kinangali","Mninga","Ngano","Katumbasongwe","Naming`ong`o","Masumbwe","Loliondo","Mbalatse","Mumilamila","Longido","Magulundinga","Allah Karim","Mgongo Rural","Kimande","Tegemeo","Cogi Oldonyosambu","Mlowo Rc","Lyamungo Gov","Kazuramimba","Mwimbi","Rongai","Nkoma","Busongola","Ngamiani","Mbalamaziwa","Mkulwe","Tweyambe","Likombora","Izazi","Ibigi Maternity","Cogi Daraja 11","Marindi Rc","Makangaga","Katoro","Lutubiga","Kongwa Rc","Luagala","Mkono Wa Mara (me)","Mkondai","Mwali","Ikomela","Niku","Zebeya","Ruanda Magereza Chuo","Kongowe","Calvary","Magazo Bakwata","Ngaleku","Sokoine Regional","Chitinde","Namwinyu","Manyara Regional","Itwangi","Katuka","Nata","Dr Masam","Maganzo","Makongo Juu.	","Ninga","Mwatasi","Nkung`ungu","Upuge","Muzye","Bubale","Utundaukulu","Msasa","Mtera","Magomeni","Madale	","Mpandangindo","Likarangilo","Busale","Chole","Kikale","Kibo","Shamajala","Kasela","Kilimatinde","Nganjoni","Nyamazugo","Mbweni Mpiji","Lusanje","Tabora Girls","Kirongo Chini","Likawage","Sali","Trc Marine","Katerere","Mtoni","Ilola","Malongwe","Luhaga","Kangaga","Ifuko","Nundu","Kitomanga","Ngalanga","Lugarawa","Msala","Balang`a","Juma Kisiwani","Kongwa Distric","Ruvu Marwa","Yombo","Kifula","Iyoka","Magoye","Sungo","Lukumbule","Mto Wa Mbu","Msufini","Kiraeni","Kwadelo","Mgambo Jk","Mabogini","Marambo","Lewa","Mnali","Magereza Mpwapwa","Lupila","Kilosa","Nyamisati","Visakazi","Kikomakoma","King`ori","Saja","Nyasamba","Mwanzugi","Kaseni","Kitisi","Ushora Magereza","Kiloleni","Kome","Women Mirongo","Madeke","Gurungu","Muhuwesi","T.m.j.","Shishiyu","Msangani","Tura","Oltukai","Leguruki Gv","Badi","Hampangala","Zahalau","Mziha","Kirongwe","Ludewa","Mfumbwe","Tinde","Mwanzugi Rc","Kaparamsenga","Seko Toure Regional","Kibwera","Nyasoko","Tumbi","Ilembula Luthera","Kibaya","Kitura","Mkinga Leo","Kijota","Ntyuka","Tma","Itinje","Geita","Chankele","Gera","Kisaka Km","Jobi","Mwalukwa","Hogoro","Matomomdo","Ruruma","Myovizi","Magereza Tukuyu","Sanawari","Mwamanenge","Nyamwilolelwa Ilemela","Irkaswa","Mutukula","Ipelele","Urwila","Mwadui Luhumbo","Kawaya","Migelegele","Kikarara","Igunda","Medewell","Lifuma","Makuyuni","Midtrus","Ksij","Bisumwa","Polisi Musoma","Holy Cross","Mkoma Ii","Nyankomogo","Chacha Wambura","Magara","Loje","Kikokwe","Mwafuguji","Ngwelo","Bupigu","Hombolo Rural","Issenye","Muhajirina Bakwata","Kisuke","Mnavira","Senga","Gerezani","Bariadi Distric","Matai","Ujindile","Korongoni","Somagedi","Mwangaza","Mpanda","Wami Dakawa","Hurumia Watoto","Mang`oto","Chihangu Rural","Gairo","Mivinjeni","Mkwedu","Hajirah","Usagari","Busolwa","Bakwata Str. 11","Kilema","Nachingwea Distric","Segala","Ocklands Magamba","Igwisi","Bukulu","Milumba","Mijelejele","Kalulu","St. Kizito","Semu","Mwaniko","Doni","Iwalanje","Butwa","Kiwira","Kweru","Luhindo","Kahangara","Mt. Rungwe","Libango","Saunyi","Buzi","Kaisho","Mkata","Magana","Majalila","Mfereji","Mkekena","Shilabela","Murusagamba","Nasindi","Bomani","Yenu","Mwamala","Mwaoga","Msoga","Namahema","Mbede","Magereza Ufundi","Mandepwende Lusewa","Huduma Mashuleni","Tambi","Nkinto","Ilungu","Mbezi Mlungwana","Majengo","Ruvuma Chini","Gongali","Masunguru","Kitagutiti","Hombolo Leprosarium","Bwembwera","Nkulabi","Kishanda","Mahege","Ndalibo","St. Marti","Ishenta","Itete","Kapele","Igoji1","Kibwe","Ndekeli","Trl","Lubeho","Mkenge","Nongwe","Chifutuka","Uvinza","Muyenje","Kikubiji","Mhaji","Mbabala B","Muzdalifa","Sasu","Mnyuzi","Kmt Yombo Darajani","Nyambori","Mji Mpya","Nandete","Igava","Ndwanga","Ntungwa","Magogoni","Ifwagi","Tungi Estate","Gidas","Visiga Kati","Maua","Maruku","Bilulumo","Hosea","Goba 	","Family","Kijenge Rc","Ihayabuyaga","Muhula","Upendo Dom","Mwasayi","Kalenge","Njombe","Beledi","Galigali","Mahenge","Iragua Govt.","Kifungilo","Nyamalapa","Isyesye","Kipangala","Merr","Singisa Missio","Msagali","Bulila","Tac","Marumba Psychiatric U","Maere","Mkongo","Magalata","Wazazi Swee","Izumbwe","Kitambuka","Mwakabuta","Bulela","Nyaumata","Linda","Musa","Kwasadala Cogi","Sahwa","Wazazi Menonite","Ligula Regional","Kwamndolwa","Kazamoyo","Pisalala","Mtumba","Amana","Ilyamchele","Ngomamtimba","Nambali","Kitandi","Ilandutwa","Mpunze","Bwawani","Runere","Lemguru","Ndorwe","Mwabulutagu","Zacci","Kilimahewa","Mkale","Katulukila","Ilambilole","Kwitanga","Fmm","Kipapa","Nssf","Nyanshana Jwtz","Nampungu","Wami Kuu Gerezani","Limage","Rundugai","Mchungu","Rukuba","Mengeni Kitasha","Samunge","Ngereyani","Malili","Twatwatwa","Sulu","Muhimbili National","Lugoba Missio","Lushoto Distric","Kikilo","Kulwa","Kyebitembe","Sawida","Wambi","Sonjo","Muungano","Kipera","Segerea","Mang`aliza","St. Joh","Kibingo","Raa","Mitawa","Sangaiwe","Mwazye","Rch A","Vugiri","Mlingotini","Tarime","Lumbiji","Lyangweni","Kipelele","Mwananimba","Khusumay","Manda Juu Rc","Unyankhanya","Geita Gold Mine","Mkonoo","Shungubweni","Mughamo","Kinampanda Elc","Sasilo","Makanjiro","Iligamba","Nyakaiga","Derema","Getamock","Mariwanda","Manawa","Kitagata","Upendo Geita","Igagala","5 11 Kj","Hamai Rhc","Mungushi","Matongoro","Chibiso","Utiri","Mtibwa Sugar","Kisangara Juu Rc","Vyadigwa","Malula","Tumbi Nzega","Kising`a","Luhunga","Mtongwele","Namalulu","Chunguruma","Kidunda","Ng`iresi","Kiloleli","Railways","Msitu Wa Tembo","Mhunze Bakwata","Buzilasoga","Mwamazengo","Pauline","Shunguliba","Ilopa","Himo Makuyuni","Ulyankulu","Kitunda","Nduamughanga","Mwanaleguma","Ihapula","Izengabatogilwe","Ruhita","Pandambili","Ulowa","Madebe","Kongogo","Emara","Fumagila","Sachita","Nyamahana","Igombe 56","Murjanda","Kigera Etuma Rc","Kariakoo","Sda Nyamatare","Rch B","Nkinga","Kasanga Missio","Mirerani","Hedaru Msamaria","Mangirikiti","Pangawe Jwtz","Mkarehe","Nachinyimba","Udinde","Mpanda Mbozi","Zunzuli","Nkoanekoli","Crct Msitu","St. Thomas","Kibutuka","Mpiji Magohe.	","Kongwa","Mugango","Ulai","Liparamba","Kitaramanka Ktm","Zagayu","Nyumbu","Uru Missio","Kaagya","Makulani","Igalamu","K`s","Kitangari Rural","Kumugamba","Ilambila","Mkululu","Mtandika","Butimba T.t.c","Sakala","Buhoro","Ccp","Lutale","Engaruka","Arusha X-ray","Nyoni","Rhoudes","Buza","Manka","Lyelembo","Maria","Mang`ola Juu","Mwanangwa","Igowole","Kasange","Issuna","Mkutani","Mkwaya","Milepa","Kwandugwa","Nangano","Katoma","Ngyani","Kikelelwa","Butuguri","Jkt Mlale","Mpiruka","Mbaragane","Magaga","Nyundo","Wimba Mahanago","Basanza","Nafuba","Meru","Bushashi","Uswaya","Bakwata Mpwapwa","Chamwino Rhc","Tmc Katumba","Kishapu Distric","Kijitonyama","Morogoro Ttc","Kidenge","Maleutsi","Palangawanu","Mico G/mboto","Pahi Gov","Kasulo","Diaconica","Madibira","Nyang`homango","Mweka Omi","Manyata","Wembere Elc","Dasico - Umasida","Matui Chapakazi","Mwalusa","Nunge","Iboya","Hamkoko","Lupata","Masiwani Shamba","Itona","Mwagiligili","Godegode","Malilita","Olarash","Kiteto","Murutunguru","Kyamyorwa","Gombero","Merya","National Housing C.","Busangi","Litumbandyosi","Magereza Mgagao","Naputa","Magaiduru","Bahi","Arafa Kiparang`anda","Passada","Mpenda","Mbuguni","Kinsabe","Kajima","Moshi Arusha Occup","Idodi","Lwande","Aic Bweri","Chipanga","Ilembo","Nyamgali","Mwanzo Mgumu","Nasibugani","Munanila","Nabungona","Tasinga","Nyengedi","Kisharita","Litumbakuhamba","Kcmc Referal","Mtitu","Ikwiriri Missio","Nkoanrua","Mingoyo","Kanisalamungu","Bumilayinga","Nyambiti","Kigogo","Pagwi","Ilemela","Wazazi Dutwa","Kharumwa","Nyamiaga","Olgilai","Kiriba","Mapanda","Lupatingatinga","Kiziba","Lipembelwasenga","Tayma Sandali","Karatu","Mahina","Kinamagi","Tazara Workshop","Ruhu","Litola","Maratani","Kilimahew A","Herembe","Sandulula","Nyabula","Ushiri","Dakawa","Old Moshi Sec.","Matongo","Robanda","Mwang`halanga","Safina Rc","Kasenga","Madilu","Gwarama","Chandarua","Sazima","Ukumbi","Dutwa","Mkunda","Manga","Mbati","Bubango","Nyakitono","Anglica","Kikatiti Luthera","St. Walburgs","Mbaha","Mwalapapa","Katumba I","Nyamunyusi","Rujewa Muslim","Seronera N.park","Nyangabo","Oldea","Ruponda","Kibondeni","Chilemba","Magadini Cogi","Irambo","Kingachi","Kibata","Nyanzwa","Maphe","I/mbeshi","Ziwani","Municipal","Nyamidaho","Moshes Specialis","Chase","Bulwa","Soga","Mukabuye","Mkole","Komkonga","Kinamweli","Mpera","Ihungo Ss","Kibo Paediatric","Rc Missio","Hedaru Rural","Namatutwe","Mt Kimwaga","Madona","Kibena","Shanga","Old Camp `jwtz`","Kiborloni","Sambasha","Koromije","Mahida","Kolomije","Mashati Rc","Nyerere Ddh","Irisya","Shambarai","Nkungwe","Lukande","Ihahi","Tumaini Biharamulo","Bwiru Girls","Unone","Mwamabanza","St. Valentine","Lukungu","Wegero","Chabruma Jwtz","St.edigio","Msaada","Kasulu Council Distric","Hondogo","Mkapa","Chanika","Suwa","Makong`onda","Shaurimoyo","Mhunze Wazazi","Lyapona","Moronga","Ndumbi","Igoma","Upendo Nurraifo","Mikanjuni","Nahima","Mfisini","Arafa Kichemchem","Kivule","Munisagara","Ng`wande","Wazazi Salawe","Izimbya","Mafiga","Ng`hoboko","Mpeto","Kalambo","Tabora Boys","Chitego","Mwabuki","Himo Red Cross","Zugimlole","Shadi","Mvumi Makulu","Itundu","Buyango","Katumbi","Kahororo","St. Mary Fatima","Vikumburu","Bakwata Sadala","Yamu","Idodoma","Iyombo","Dindira","Kumubanga","Luchili","Mtambo","Sda -pasiansi","Ihanga Chato","Kinonko","Mboni","Mikumi Cvm","Kasuguti","Kasisa","Mhinduro","Mwaswale","Italagwe","Fundimbanga","Itete Missio","Izimbili","Njelenje","Igundu","Mlandala","Rwamkoma Jk","Kiwanga","Rc Iyunga","Kawekapina","Kirikiri Bakwata","Bugando","Manka Luthera","Bilila","Mwemage","Gumboneka","Ayasanda","Same","Msambiazi","Kimara.	","Msufini Kidete","Katandala","Musoma Regional","Mhonda Missio","Nyamoli","Mandela","Kimwani","Pande","Mwamgongo","Isima"
        );
    }

    /**
     * Returns Array of dummy organisationunits
     *
     * @return array
     */
    public function addDummyOrganisationunits()
    {
        // Load Public Data
        $this->organisationunits = Array(
            0=>Array(
                'shortname'=>'mohsw',
                'longname'=>'Ministry Of Health',
                'active'=>false,
                'parent'=>Null,
                'description'=>'Ministry of Health and Social welfare'),
            1=>Array(
                'shortname'=>'hti',
                'longname'=>'Health Training Institutions',
                'active'=>false,
                'parent'=>'mohsw',
                'description'=>'Health Training Instituions'),
            2=>Array(
                'shortname'=>'mohswagencies',
                'longname'=>'Mohsw Agencies',
                'active'=>false,
                'parent'=>'mohsw',
                'description'=>'Ministry of Health Agencies'),
            3=>Array(
                'shortname'=>'mohswdepartments',
                'longname'=>'Mohsw Departments',
                'active'=>false,
                'parent'=>'mohsw',
                'description'=>'Ministry of Health Departments'),
            4=>Array(
                'shortname'=>'referralhosps',
                'longname'=>'Referral Hospitals',
                'active'=>false,
                'parent'=>'mohsw',
                'description'=>'Referral Hospitals'),
            5=>Array(
                'shortname'=>'regions',
                'longname'=>'Regions',
                'active'=>false,
                'parent'=>'mohsw',
                'description'=>'Regions'),
            // Regions
            6=>Array(
                'shortname'=>'arusha',
                'longname'=>'Arusha Region',
                'active'=>false,
                'parent'=>'regions'),
            7=>Array(
                'shortname'=>'daressalaam',
                'longname'=>'Dar Es Salaam Region',
                'active'=>false,
                'parent'=>'regions'),
            8=>Array(
                'shortname'=>'dodoma',
                'longname'=>'Dodoma Region',
                'active'=>false,
                'parent'=>'regions'),
            9=>Array(
                'shortname'=>'iringa',
                'longname'=>'Iringa Region',
                'active'=>false,
                'parent'=>'regions'),
            10=>Array(
                'shortname'=>'kagera',
                'longname'=>'Kagera Region',
                'active'=>false,
                'parent'=>'regions'),
            11=>Array(
                'shortname'=>'kigoma',
                'longname'=>'Kigoma Region',
                'active'=>false,
                'parent'=>'regions'),
            12=>Array(
                'shortname'=>'kilimanjaro',
                'longname'=>'Kilimanjaro Region',
                'active'=>false,
                'parent'=>'regions'),
            13=>Array(
                'shortname'=>'lindi',
                'longname'=>'Lindi Region',
                'active'=>false,
                'parent'=>'regions'),
            14=>Array(
                'shortname'=>'manyara',
                'longname'=>'Manyara Region',
                'active'=>false,
                'parent'=>'regions'),
            15=>Array(
                'shortname'=>'mara',
                'longname'=>'Mara Region',
                'active'=>false,
                'parent'=>'regions'),
            16=>Array(
                'shortname'=>'mbeya',
                'longname'=>'Mbeya Region',
                'active'=>false,
                'parent'=>'regions'),
            17=>Array(
                'shortname'=>'morogoro',
                'longname'=>'Morogoro Region',
                'active'=>false,
                'parent'=>'regions'),
            18=>Array(
                'shortname'=>'mtwara',
                'longname'=>'Mtwara Region',
                'active'=>false,
                'parent'=>'regions'),
            19=>Array(
                'shortname'=>'mwanza',
                'longname'=>'Mwanza Region',
                'active'=>false,
                'parent'=>'regions'),
            20=>Array(
                'shortname'=>'pwani',
                'longname'=>'Pwani Region',
                'active'=>false,
                'parent'=>'regions'),
            21=>Array(
                'shortname'=>'rukwa',
                'longname'=>'Rukwa Region',
                'active'=>false,
                'parent'=>'regions'),
            22=>Array(
                'shortname'=>'ruvuma',
                'longname'=>'Ruvuma Region',
                'active'=>false,
                'parent'=>'regions'),
            23=>Array(
                'shortname'=>'shinyanga',
                'longname'=>'Shinyanga Region',
                'active'=>false,
                'parent'=>'regions'),
            24=>Array(
                'shortname'=>'singida',
                'longname'=>'Singida Region',
                'active'=>false,
                'parent'=>'regions'),
            25=>Array(
                'shortname'=>'tabora',
                'longname'=>'Tabora Region',
                'active'=>false,
                'parent'=>'regions'),
            26=>Array(
                'shortname'=>'tanga',
                'longname'=>'Tanga Region',
                'active'=>false,
                'parent'=>'regions'),
            //Arusha distructs
            27=>Array(
                'shortname'=>'arushacc',
                'longname'=>'Arusha City Council',
                'active'=>false,
                'parent'=>'arusha'),
            28=>Array(
                'shortname'=>'arushadc',
                'longname'=>'Arusha District Council',
                'active'=>false,
                'parent'=>'arusha'),
            29=>Array(
                'shortname'=>'karatudc',
                'longname'=>'Karatu District Council',
                'active'=>false,
                'parent'=>'arusha'),
            30=>Array(
                'shortname'=>'longidodc',
                'longname'=>'Longido District Council',
                'active'=>false,
                'parent'=>'arusha'),
            31=>Array(
                'shortname'=>'merudc',
                'longname'=>'Meru District Council',
                'active'=>false,
                'parent'=>'arusha'),
            32=>Array(
                'shortname'=>'modulidc',
                'longname'=>'Monduli District Council',
                'active'=>false,
                'parent'=>'arusha'),
            33=>Array(
                'shortname'=>'mtmerureghsp',
                'longname'=>'Mount Meru Regional Hospital',
                'active'=>true,
                'parent'=>'arusha'),
            34=>Array(
                'shortname'=>'ngorongorodc',
                'longname'=>'Ngorongoro District Council',
                'active'=>false,
                'parent'=>'arusha'),
            //Dar es salaam districts
            35=>Array(
                'shortname'=>'ilalamc',
                'longname'=>'Ilala Municipal Council',
                'active'=>false,
                'parent'=>'daressalaam'),
            36=>Array(
                'shortname'=>'kinondonimc',
                'longname'=>'Kinondoni Municipal Council',
                'active'=>false,
                'parent'=>'daressalaam'),
            37=>Array(
                'shortname'=>'temekemc',
                'longname'=>'Temeke Municipal Council',
                'active'=>false,
                'parent'=>'daressalaam'),
            // Referral hospitals
            38=>Array(
                'shortname'=>'bugandorefhsp',
                'longname'=>'Bugando Referral Hospital',
                'active'=>true,
                'parent'=>'referralhosps'),
            39=>Array(
                'shortname'=>'kcmcrefhsp',
                'longname'=>'Kcmc Referal Hospital',
                'active'=>true,
                'parent'=>'referralhosps'),
            40=>Array(
                'shortname'=>'kibongototbhsp',
                'longname'=>'Kibong`oto Tb Hospital',
                'active'=>true,
                'parent'=>'referralhosps'),
            41=>Array(
                'shortname'=>'mbeyarefhsp',
                'longname'=>'Mbeya Referral Hospital',
                'active'=>true,
                'parent'=>'referralhosps'),
            42=>Array(
                'shortname'=>'miremberefhsp',
                'longname'=>'Mirembe Referral Hospital',
                'active'=>true,
                'parent'=>'referralhosps'),
            43=>Array(
                'shortname'=>'muhimbilinathsp',
                'longname'=>'Muhimbili National Hospital',
                'active'=>true,
                'parent'=>'referralhosps'),
            44=>Array(
                'shortname'=>'muhimbiliorthinst',
                'longname'=>'Muhimbili Orthpeadic Institute',
                'active'=>true,
                'parent'=>'referralhosps'),
            45=>Array(
                'shortname'=>'oceanroadcancinst',
                'longname'=>'Ocean Road Cancer Institute',
                'active'=>true,
                'parent'=>'referralhosps'),
            // Departments
            46=>Array(
                'shortname'=>'adminhrdvn',
                'longname'=>'Administration And Human Resources Division',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            47=>Array(
                'shortname'=>'cmo',
                'longname'=>'Chief Medical Office',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            48=>Array(
                'shortname'=>'cno',
                'longname'=>'Chief Nursing Office',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            49=>Array(
                'shortname'=>'financenaccounts',
                'longname'=>'Finance & Accounts',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            50=>Array(
                'shortname'=>'infoncommtech',
                'longname'=>'Information And Communication Technology Unit',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            51=>Array(
                'shortname'=>'internalaudit',
                'longname'=>'Internal Audit Unit',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            52=>Array(
                'shortname'=>'policynplanningdvn',
                'longname'=>'Policy And Planning Division',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            53=>Array(
                'shortname'=>'socialwelfaredvn',
                'longname'=>'Social Welfare Division',
                'active'=>false,
                'parent'=>'mohswdepartments'),
            // Agencies
            54=>Array(
                'shortname'=>'enviornhc',
                'longname'=>'Environmental Health Council',
                'active'=>false,
                'parent'=>'mohswagencies'),
            55=>Array(
                'shortname'=>'foodnnutritionc',
                'longname'=>'Food And Nutrition Centre',
                'active'=>false,
                'parent'=>'mohswagencies'),
            56=>Array(
                'shortname'=>'governcla',
                'longname'=>'Government Chemist Laboratory Authority',
                'active'=>false,
                'parent'=>'mohswagencies'),
            57=>Array(
                'shortname'=>'medicalcoftang',
                'longname'=>'Medical Council Of Tanganyika',
                'active'=>false,
                'parent'=>'mohswagencies'),
            58=>Array(
                'shortname'=>'natmedresearch',
                'longname'=>'National Medical Research',
                'active'=>false,
                'parent'=>'mohswagencies'),
            59=>Array(
                'shortname'=>'nursinnmidwfc',
                'longname'=>'Nursing And Midwifery Center',
                'active'=>false,
                'parent'=>'mohswagencies'),
            60=>Array(
                'shortname'=>'optometryc',
                'longname'=>'Optometry Council',
                'active'=>false,
                'parent'=>'mohswagencies'),
            61=>Array(
                'shortname'=>'pharmacyc',
                'longname'=>'Pharmacy Council',
                'active'=>false,
                'parent'=>'mohswagencies'),
            62=>Array(
                'shortname'=>'radiologynimgc',
                'longname'=>'Radiology And Imaging Council',
                'active'=>false,
                'parent'=>'mohswagencies'),
            63=>Array(
                'shortname'=>'tzfoodndruga',
                'longname'=>'Tanzania Food And Drug Authoriy',
                'active'=>false,
                'parent'=>'mohswagencies'),
            64=>Array(
                'shortname'=>'tradalthealth',
                'longname'=>'Traditional Alternative Health',
                'active'=>false,
                'parent'=>'mohswagencies'),
            //Training institutions
            65=>Array(
                'shortname'=>'bagamoyons',
                'longname'=>'Bagamoyo Nursing School',
                'active'=>true,
                'parent'=>'hti'),
            66=>Array(
                'shortname'=>'bugandosml',
                'longname'=>'Bugando School Of Medical Laboratory',
                'active'=>true,
                'parent'=>'hti'),
            67=>Array(
                'shortname'=>'bugandosnt',
                'longname'=>'Bugando School Of Nurse Teachers',
                'active'=>true,
                'parent'=>'hti'),
            68=>Array(
                'shortname'=>'bugandoson',
                'longname'=>'Bugando School Of Nursing',
                'active'=>true,
                'parent'=>'hti'),
            69=>Array(
                'shortname'=>'bugandosor',
                'longname'=>'Bugando School Of Radiography',
                'active'=>true,
                'parent'=>'hti'),
            70=>Array(
                'shortname'=>'bulogwadts',
                'longname'=>'Bulogwa Dental Therapist School',
                'active'=>true,
                'parent'=>'hti'),
            71=>Array(
                'shortname'=>'centrefedfha',
                'longname'=>'Centre For Education Development For Health Arusha',
                'active'=>true,
                'parent'=>'hti'),
            72=>Array(
                'shortname'=>'collegeofhs',
                'longname'=>'College Of Health Science',
                'active'=>true,
                'parent'=>'hti'),
            73=>Array(
                'shortname'=>'collegeofmlruco',
                'longname'=>'College Of Medical Lab. RUCO',
                'active'=>true,
                'parent'=>'hti'),
            74=>Array(
                'shortname'=>'cotcnkingatab',
                'longname'=>'COTC Nkinga Tabora',
                'active'=>true,
                'parent'=>'hti'),
            75=>Array(
                'shortname'=>'daredans',
                'longname'=>'Dareda Nursing School',
                'active'=>true,
                'parent'=>'hti'),
            //Dodoma region districts
            76=>Array(
                'shortname'=>'bahidc',
                'longname'=>'Bahi District Council',
                'active'=>false,
                'parent'=>'dodoma'),
            77=>Array(
                'shortname'=>'chamwinodc',
                'longname'=>'Chamwino District Council',
                'active'=>false,
                'parent'=>'dodoma'),
            78=>Array(
                'shortname'=>'dodomamc',
                'longname'=>'Dodoma Municipal Council',
                'active'=>false,
                'parent'=>'dodoma'),
            79=>Array(
                'shortname'=>'dodomareghsp',
                'longname'=>'Dodoma Regional Hospital',
                'active'=>false,
                'parent'=>'dodoma'),
            80=>Array(
                'shortname'=>'kondoadc',
                'longname'=>'Kondoa District Council',
                'active'=>false,
                'parent'=>'dodoma'),
            81=>Array(
                'shortname'=>'kongwadc',
                'longname'=>'Kongwa District Council',
                'active'=>false,
                'parent'=>'dodoma'),
            82=>Array(
                'shortname'=>'mpwapwadc',
                'longname'=>'Mpwapwa District Council',
                'active'=>false,
                'parent'=>'dodoma'),
            // Iringa Region districts
            83=>Array(
                'shortname'=>'iringadc',
                'longname'=>'Iringa District Council',
                'active'=>true,
                'parent'=>'iringa'),
            84=>Array(
                'shortname'=>'iringamc',
                'longname'=>'Iringa Municipal Council',
                'active'=>true,
                'parent'=>'iringa'),
            85=>Array(
                'shortname'=>'kilolodc',
                'longname'=>'Kilolo District Council',
                'active'=>true,
                'parent'=>'iringa'),
            86=>Array(
                'shortname'=>'ludewadc',
                'longname'=>'Ludewa District Council',
                'active'=>true,
                'parent'=>'iringa'),
            87=>Array(
                'shortname'=>'maketedc',
                'longname'=>'Makete District Council',
                'active'=>true,
                'parent'=>'iringa'),
            88=>Array(
                'shortname'=>'mufindidc',
                'longname'=>'Mufindi District Council',
                'active'=>true,
                'parent'=>'iringa'),
            89=>Array(
                'shortname'=>'njombedc',
                'longname'=>'Njombe District Council',
                'active'=>true,
                'parent'=>'iringa'),
            90=>Array(
                'shortname'=>'njombetc',
                'longname'=>'Njombe Town Council',
                'active'=>true,
                'parent'=>'iringa'),
            91=>Array(
                'shortname'=>'iringareghsp',
                'longname'=>'Iringa Regional Hospital',
                'active'=>true,
                'parent'=>'iringa'),
            // Kagera Region districts
            92=>Array(
                'shortname'=>'biharamulodc',
                'longname'=>'Biharamulo District Council',
                'active'=>true,
                'parent'=>'kagera'),
            93=>Array(
                'shortname'=>'bukobadc',
                'longname'=>'Bukoba District Council',
                'active'=>true,
                'parent'=>'kagera'),
            94=>Array(
                'shortname'=>'bukobamc',
                'longname'=>'Bukoba Municipal Council',
                'active'=>true,
                'parent'=>'kagera'),
            95=>Array(
                'shortname'=>'chatodc',
                'longname'=>'Chato District Council',
                'active'=>true,
                'parent'=>'kagera'),
            96=>Array(
                'shortname'=>'karagwedc',
                'longname'=>'Karagwe District Council',
                'active'=>true,
                'parent'=>'kagera'),
            97=>Array(
                'shortname'=>'misenyidc',
                'longname'=>'Misenyi District Council',
                'active'=>true,
                'parent'=>'kagera'),
            98=>Array(
                'shortname'=>'mulebadc',
                'longname'=>'Muleba District Council',
                'active'=>true,
                'parent'=>'kagera'),
            99=>Array(
                'shortname'=>'ngaradc',
                'longname'=>'Ngara District Council',
                'active'=>true,
                'parent'=>'kagera'),
            100=>Array(
                'shortname'=>'kagerareghsp',
                'longname'=>'Kagera Regional Hospital',
                'active'=>true,
                'parent'=>'kagera'),
            // Kigoma Region districts
            101=>Array(
                'shortname'=>'kasuludc',
                'longname'=>'Kasulu District Council',
                'active'=>true,
                'parent'=>'kigoma'),
            102=>Array(
                'shortname'=>'kibondodc',
                'longname'=>'Kibondo District Council',
                'active'=>true,
                'parent'=>'kigoma'),
            103=>Array(
                'shortname'=>'kigomadc',
                'longname'=>'Kigoma District Council',
                'active'=>true,
                'parent'=>'kigoma'),
            104=>Array(
                'shortname'=>'kigomamc',
                'longname'=>'Kigoma Municipal Council',
                'active'=>true,
                'parent'=>'kigoma'),
            105=>Array(
                'shortname'=>'mawenireghsp',
                'longname'=>'Maweni Regional Hospital',
                'active'=>true,
                'parent'=>'kigoma'),
            // Kilimanjaro Region districts
            106=>Array(
                'shortname'=>'haidc',
                'longname'=>'Hai District Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            107=>Array(
                'shortname'=>'moshidc',
                'longname'=>'Moshi District Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            108=>Array(
                'shortname'=>'moshimc',
                'longname'=>'Moshi Municipal Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            109=>Array(
                'shortname'=>'mwangadc',
                'longname'=>'Mwanga District Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            110=>Array(
                'shortname'=>'rombodc',
                'longname'=>'Rombo District Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            111=>Array(
                'shortname'=>'samedc',
                'longname'=>'Same District Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            112=>Array(
                'shortname'=>'sihadc',
                'longname'=>'Siha District Council',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            113=>Array(
                'shortname'=>'mawenzireghsp',
                'longname'=>'Mawenzi Regional Hospital',
                'active'=>true,
                'parent'=>'kilimanjaro'),
            // Lindi Region districts
            114=>Array(
                'shortname'=>'kilwadc',
                'longname'=>'Kilwa District Council',
                'active'=>true,
                'parent'=>'lindi'),
            1115=>Array(
                'shortname'=>'lindidc',
                'longname'=>'Lindi District Council',
                'active'=>true,
                'parent'=>'lindi'),
            116=>Array(
                'shortname'=>'lindimc',
                'longname'=>'Lindi Municipal Council',
                'active'=>true,
                'parent'=>'lindi'),
            117=>Array(
                'shortname'=>'liwaledc',
                'longname'=>'Liwale District Council',
                'active'=>true,
                'parent'=>'lindi'),
            118=>Array(
                'shortname'=>'nachingweadc',
                'longname'=>'Nachingwea District Council',
                'active'=>true,
                'parent'=>'lindi'),
            119=>Array(
                'shortname'=>'raungwadc',
                'longname'=>'Ruangwa District Council',
                'active'=>true,
                'parent'=>'lindi'),
            120=>Array(
                'shortname'=>'sokoinereghsp',
                'longname'=>'Sokoine Regional Hospital',
                'active'=>true,
                'parent'=>'lindi'),
            // Manyara Region districts
            121=>Array(
                'shortname'=>'babatidc',
                'longname'=>'Babati District Council',
                'active'=>true,
                'parent'=>'manyara'),
            122=>Array(
                'shortname'=>'babatitc',
                'longname'=>'Babati Town Council',
                'active'=>true,
                'parent'=>'manyara'),
            123=>Array(
                'shortname'=>'hanangdc',
                'longname'=>'Hanang District Council',
                'active'=>true,
                'parent'=>'manyara'),
            124=>Array(
                'shortname'=>'kitetodc',
                'longname'=>'Kiteto District Council',
                'active'=>true,
                'parent'=>'manyara'),
            125=>Array(
                'shortname'=>'mbuludc',
                'longname'=>'Mbulu District Council',
                'active'=>true,
                'parent'=>'manyara'),
            126=>Array(
                'shortname'=>'simanjirodc',
                'longname'=>'Simanjiro District Council',
                'active'=>true,
                'parent'=>'manyara'),
            127=>Array(
                'shortname'=>'manyarareghsp',
                'longname'=>'Manyara Regional Hospital',
                'active'=>true,
                'parent'=>'manyara'),
            // Mara Region districts
            128=>Array(
                'shortname'=>'bundadc',
                'longname'=>'Bunda District Council',
                'active'=>true,
                'parent'=>'mara'),
            129=>Array(
                'shortname'=>'musomadc',
                'longname'=>'Musoma District Council',
                'active'=>true,
                'parent'=>'mara'),
            130=>Array(
                'shortname'=>'musomamc',
                'longname'=>'Musoma Municipal Council',
                'active'=>true,
                'parent'=>'mara'),
            131=>Array(
                'shortname'=>'roryadc',
                'longname'=>'Rorya District Council',
                'active'=>true,
                'parent'=>'mara'),
            132=>Array(
                'shortname'=>'serengetidc',
                'longname'=>'Serengeti District Council',
                'active'=>true,
                'parent'=>'mara'),
            133=>Array(
                'shortname'=>'tarimedc',
                'longname'=>'Tarime District Council',
                'active'=>true,
                'parent'=>'mara'),
            134=>Array(
                'shortname'=>'musomareghsp',
                'longname'=>'Musoma Regional Hospital',
                'active'=>true,
                'parent'=>'mara'),
            // Mbeya Region districts
            135=>Array(
                'shortname'=>'chunyadc',
                'longname'=>'Chunya District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            136=>Array(
                'shortname'=>'ilejedc',
                'longname'=>'Ileje District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            137=>Array(
                'shortname'=>'kyeladc',
                'longname'=>'Kyela District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            138=>Array(
                'shortname'=>'mbaralidc',
                'longname'=>'Mbarali District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            139=>Array(
                'shortname'=>'mbeyacc',
                'longname'=>'Mbeya City Council',
                'active'=>true,
                'parent'=>'mbeya'),
            140=>Array(
                'shortname'=>'mbeyadc',
                'longname'=>'Mbeya District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            141=>Array(
                'shortname'=>'mbozidc',
                'longname'=>'Mbozi District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            142=>Array(
                'shortname'=>'rungwedc',
                'longname'=>'Rungwe District Council',
                'active'=>true,
                'parent'=>'mbeya'),
            143=>Array(
                'shortname'=>'mbeyareghsp',
                'longname'=>'Mbeya Regional Hospital',
                'active'=>true,
                'parent'=>'mbeya'),
            // Morogoro Region districts
            144=>Array(
                'shortname'=>'kilomberodc',
                'longname'=>'Kilombero District Council',
                'active'=>true,
                'parent'=>'morogoro'),
            145=>Array(
                'shortname'=>'kilosadc',
                'longname'=>'Kilosa District Council',
                'active'=>true,
                'parent'=>'morogoro'),
            146=>Array(
                'shortname'=>'morogorodc',
                'longname'=>'Morogoro District Council',
                'active'=>true,
                'parent'=>'morogoro'),
            147=>Array(
                'shortname'=>'morogoromc',
                'longname'=>'Morogoro Municipal Council',
                'active'=>true,
                'parent'=>'morogoro'),
            148=>Array(
                'shortname'=>'mvomerodc',
                'longname'=>'Mvomero District Council',
                'active'=>true,
                'parent'=>'morogoro'),
            149=>Array(
                'shortname'=>'ulangadc',
                'longname'=>'Ulanga District Council',
                'active'=>true,
                'parent'=>'morogoro'),
            150=>Array(
                'shortname'=>'morogororeghsp',
                'longname'=>'Morogoro Regional Hospital',
                'active'=>true,
                'parent'=>'morogoro'),
            // Mtwara Region districts
            151=>Array(
                'shortname'=>'masasidc',
                'longname'=>'Masasi District Council',
                'active'=>true,
                'parent'=>'mtwara'),
            152=>Array(
                'shortname'=>'mtwaradc',
                'longname'=>'Mtwara District Council',
                'active'=>true,
                'parent'=>'mtwara'),
            153=>Array(
                'shortname'=>'mtwaramc',
                'longname'=>'Mtwara Municipal Council',
                'active'=>true,
                'parent'=>'mtwara'),
            154=>Array(
                'shortname'=>'nanyumbudc',
                'longname'=>'Nanyumbu District Council',
                'active'=>true,
                'parent'=>'mtwara'),
            155=>Array(
                'shortname'=>'newaladc',
                'longname'=>'Newala District Council',
                'active'=>true,
                'parent'=>'mtwara'),
            156=>Array(
                'shortname'=>'tandahimbadc',
                'longname'=>'Tandahimba District Council',
                'active'=>true,
                'parent'=>'mtwara'),
            157=>Array(
                'shortname'=>'ligulareghsp',
                'longname'=>'Ligula Regional Hospital',
                'active'=>true,
                'parent'=>'mtwara'),
            // Mwanza Region districts
            158=>Array(
                'shortname'=>'geitadc',
                'longname'=>'Geita District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            159=>Array(
                'shortname'=>'ilemeladc',
                'longname'=>'Ilemela District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            160=>Array(
                'shortname'=>'kwimbadc',
                'longname'=>'Kwimba District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            161=>Array(
                'shortname'=>'magudc',
                'longname'=>'Magu District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            162=>Array(
                'shortname'=>'misungwidc',
                'longname'=>'Misungwi District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            163=>Array(
                'shortname'=>'nyamaganadc',
                'longname'=>'Nyamagana District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            164=>Array(
                'shortname'=>'sengeremadc',
                'longname'=>'Sengerema District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            165=>Array(
                'shortname'=>'ukerewedc',
                'longname'=>'Ukerewe District Council',
                'active'=>true,
                'parent'=>'mwanza'),
            166=>Array(
                'shortname'=>'sekotourereghsp',
                'longname'=>'Seko Toure Regional Hospital',
                'active'=>true,
                'parent'=>'mwanza'),
            // Pwani Region districts
            167=>Array(
                'shortname'=>'bagamoyodc',
                'longname'=>'Bagamoyo District Council',
                'active'=>true,
                'parent'=>'pwani'),
            168=>Array(
                'shortname'=>'kibahadc',
                'longname'=>'Kibaha District Council',
                'active'=>true,
                'parent'=>'pwani'),
            169=>Array(
                'shortname'=>'kibahatc',
                'longname'=>'Kibaha Town Council',
                'active'=>true,
                'parent'=>'pwani'),
            170=>Array(
                'shortname'=>'kisarawedc',
                'longname'=>'Kisarawe District Council',
                'active'=>true,
                'parent'=>'pwani'),
            171=>Array(
                'shortname'=>'mafiadc',
                'longname'=>'Mafia District Council',
                'active'=>true,
                'parent'=>'pwani'),
            172=>Array(
                'shortname'=>'mkurangadc',
                'longname'=>'Mkuranga District Council',
                'active'=>true,
                'parent'=>'pwani'),
            173=>Array(
                'shortname'=>'rufijidc',
                'longname'=>'Rufiji District Council',
                'active'=>true,
                'parent'=>'pwani'),
            // Rukwa Region districts
            174=>Array(
                'shortname'=>'mpandadc',
                'longname'=>'Mpanda District Council',
                'active'=>true,
                'parent'=>'rukwa'),
            175=>Array(
                'shortname'=>'mpandatc',
                'longname'=>'Mpanda Town Council',
                'active'=>true,
                'parent'=>'rukwa'),
            176=>Array(
                'shortname'=>'nkasidc',
                'longname'=>'Nkasi District Council',
                'active'=>true,
                'parent'=>'rukwa'),
            177=>Array(
                'shortname'=>'sumbawangadc',
                'longname'=>'Sumbawanga District Council',
                'active'=>true,
                'parent'=>'rukwa'),
            178=>Array(
                'shortname'=>'sumbawangareghsp',
                'longname'=>'Sumbawanga Regional Hospital',
                'active'=>true,
                'parent'=>'rukwa'),
            // Ruvuma Region districts
            179=>Array(
                'shortname'=>'mbingadc',
                'longname'=>'Mbinga District Council',
                'active'=>true,
                'parent'=>'ruvuma'),
            180=>Array(
                'shortname'=>'namtumbodc',
                'longname'=>'Namtumbo District Council',
                'active'=>true,
                'parent'=>'ruvuma'),
            181=>Array(
                'shortname'=>'songeadc',
                'longname'=>'Songea District Council',
                'active'=>true,
                'parent'=>'ruvuma'),
            182=>Array(
                'shortname'=>'songeamc',
                'longname'=>'Songea Municipal Council',
                'active'=>true,
                'parent'=>'ruvuma'),
            183=>Array(
                'shortname'=>'tundurudc',
                'longname'=>'Tunduru District Council',
                'active'=>true,
                'parent'=>'ruvuma'),
            184=>Array(
                'shortname'=>'ruvumareghsp',
                'longname'=>'Ruvuma Regional Hospital',
                'active'=>true,
                'parent'=>'ruvuma'),
            // Shinyanga Region districts
            185=>Array(
                'shortname'=>'bariadidc',
                'longname'=>'Bariadi District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            186=>Array(
                'shortname'=>'bukombedc',
                'longname'=>'Bukombe District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            187=>Array(
                'shortname'=>'kahamadc',
                'longname'=>'Kahama District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            188=>Array(
                'shortname'=>'kishapudc',
                'longname'=>'Kishapu District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            189=>Array(
                'shortname'=>'maswadc',
                'longname'=>'Maswa District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            190=>Array(
                'shortname'=>'meatudc',
                'longname'=>'Meatu District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            191=>Array(
                'shortname'=>'shinyangadc',
                'longname'=>'Shinyanga District Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            192=>Array(
                'shortname'=>'shinyangamc',
                'longname'=>'Shinyanga Municipal Council',
                'active'=>true,
                'parent'=>'shinyanga'),
            193=>Array(
                'shortname'=>'shinyangareghsp',
                'longname'=>'Shinyanga Regional Hospital',
                'active'=>true,
                'parent'=>'shinyanga'),
            // Singida Region districts
            194=>Array(
                'shortname'=>'irambadc',
                'longname'=>'Iramba District Council',
                'active'=>true,
                'parent'=>'singida'),
            195=>Array(
                'shortname'=>'manyonidc',
                'longname'=>'Manyoni District Council',
                'active'=>true,
                'parent'=>'singida'),
            196=>Array(
                'shortname'=>'singidadc',
                'longname'=>'Singida District Council',
                'active'=>true,
                'parent'=>'singida'),
            197=>Array(
                'shortname'=>'singidamc',
                'longname'=>'Singida Municipal Council',
                'active'=>true,
                'parent'=>'singida'),
            198=>Array(
                'shortname'=>'singidareghsp',
                'longname'=>'Singida Regional Hospital',
                'active'=>true,
                'parent'=>'singida'),
            // Tabora Region districts
            199=>Array(
                'shortname'=>'igungadc',
                'longname'=>'Igunga District Council',
                'active'=>true,
                'parent'=>'tabora'),
            200=>Array(
                'shortname'=>'nzegadc',
                'longname'=>'Nzega District Council',
                'active'=>true,
                'parent'=>'tabora'),
            201=>Array(
                'shortname'=>'sikongedc',
                'longname'=>'Sikonge District Council',
                'active'=>true,
                'parent'=>'tabora'),
            202=>Array(
                'shortname'=>'taboramc',
                'longname'=>'Tabora Municipal Council',
                'active'=>true,
                'parent'=>'tabora'),
            203=>Array(
                'shortname'=>'urambodc',
                'longname'=>'Urambo District Council',
                'active'=>true,
                'parent'=>'tabora'),
            204=>Array(
                'shortname'=>'uyuidc',
                'longname'=>'Uyui District Council',
                'active'=>true,
                'parent'=>'tabora'),
            205=>Array(
                'shortname'=>'taborareghsp',
                'longname'=>'Tabora Regional Hospital',
                'active'=>true,
                'parent'=>'tabora'),
            // Tanga Region districts
            206=>Array(
                'shortname'=>'handenidc',
                'longname'=>'Handeni District Council',
                'active'=>true,
                'parent'=>'tanga'),
            207=>Array(
                'shortname'=>'kilindidc',
                'longname'=>'Kilindi District Council',
                'active'=>true,
                'parent'=>'tanga'),
            208=>Array(
                'shortname'=>'korogwedc',
                'longname'=>'Korogwe District Council',
                'active'=>true,
                'parent'=>'tanga'),
            209=>Array(
                'shortname'=>'korogwetc',
                'longname'=>'Korogowe Town Council',
                'active'=>true,
                'parent'=>'tanga'),
            210=>Array(
                'shortname'=>'lushotodc',
                'longname'=>'Lushoto District Council',
                'active'=>true,
                'parent'=>'tanga'),
            211=>Array(
                'shortname'=>'mkingadc',
                'longname'=>'Mkinga District Council',
                'active'=>true,
                'parent'=>'tanga'),
            212=>Array(
                'shortname'=>'muhezadc',
                'longname'=>'Muheza District Council',
                'active'=>true,
                'parent'=>'tanga'),
            213=>Array(
                'shortname'=>'panganidc',
                'longname'=>'Pangani District Council',
                'active'=>true,
                'parent'=>'tanga'),
            214=>Array(
                'shortname'=>'tangamc',
                'longname'=>'Tanga Municipal Council',
                'active'=>true,
                'parent'=>'tanga'),
            215=>Array(
                'shortname'=>'bomboreghsp',
                'longname'=>'Bombo Regional Hospital',
                'active'=>true,
                'parent'=>'tanga'),
            // Chief Medical Office Divisions
            216=>Array(
                'shortname'=>'curativesrvdvn',
                'longname'=>'Curative Services Division',
                'active'=>false,
                'parent'=>'cmo'),
            217=>Array(
                'shortname'=>'healthqadvn',
                'longname'=>'Health Quality Assurance Division',
                'active'=>false,
                'parent'=>'cmo'),
            218=>Array(
                'shortname'=>'hrdvpdvn',
                'longname'=>'Human resource Development Division',
                'active'=>false,
                'parent'=>'cmo'),
            219=>Array(
                'shortname'=>'prvntvsrvcdvn',
                'longname'=>'Preventive Services Division',
                'active'=>false,
                'parent'=>'cmo'),
        );
        return $this->organisationunits;
    }

    /**
     * Returns Array of dummy organisationunits
     *
     * @param $organisationunitReference
     * @return array
     */
    public function addToIndexedOrganisationunit($organisationunitReference)
    {
        // Load Public Data
        $this->indexedOrganisationunits[$this->index++] = $organisationunitReference;
        return $this->indexedOrganisationunits;
    }

    /**
     * Dummy organisationunitLevels
     *
     * @var organisationunitLevels
     */
    private $organisationunitLevels;

    /**
     * Returns Array of organisationunitlevel fixtures
     *
     * @return mixed
     */
    public function getOrganisationunitLevels()
    {
        return $this->organisationunitLevels;
    }

    /**
     * Returns Array of dummy organisationunits
     *
     * @return array
     */
    public function addDummyOrganisationunitLevels()
    {
        // Load Public Data
        $this->organisationunitLevels = Array(
            0=>Array(
                'name'=>'Level 1',
                'level'=>1,
                'description'=>'Highest level of organisation unit tree',
                'dataentrylevel'=>False),
            1=>Array(
                'name'=>'Level 2',
                'level'=>2,
                'description'=>'Divisions Level of Ministry',
                'dataentrylevel'=>False),
            2=>Array(
                'name'=>'Level 3',
                'level'=>3,
                'description'=>'Third administrative level',
                'dataentrylevel'=>False),
            3=>Array(
                'name'=>'Level 4',
                'level'=>4,
                'description'=>'Fourth administrative level',
                'dataentrylevel'=>True),
            4=>Array(
                'name'=>'Level 5',
                'level'=>5,
                'description'=>'Lowest level of service provision',
                'dataentrylevel'=>False),
        );
        return $this->organisationunitLevels;
    }

    /**
     * Loads metadata into the database
     *
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
	{
        $this->addDummyOrganisationunits();
        // Dummy organisationunit levels
        $this->addDummyOrganisationunitLevels();
        $this->addDummyOrganisationunitNames();

        // Keep Array of distinct parent and lognames existing
        $distinctLongnameAndParent = Array();

        // Populate dummy organisationunits
		foreach($this->organisationunits as $organisationunitKey=>$humanResourceOrganisationunit) {
			$organisationunit = new Organisationunit();
            $organisationunit->setCode($humanResourceOrganisationunit['shortname']);
            $organisationunit->setShortname($humanResourceOrganisationunit['shortname']);
            $organisationunit->setLongname($humanResourceOrganisationunit['longname']);
            if(isset($humanResourceOrganisationunit['description']) && !empty($humanResourceOrganisationunit['description'])){
                $organisationunit->setDescription($humanResourceOrganisationunit['description']);
            }

            if(isset($humanResourceOrganisationunit['parent']) && !empty($humanResourceOrganisationunit['parent'])) {
                $parentReference = strtolower(str_replace(' ','',$humanResourceOrganisationunit['parent'])).'-organisationunit';
                $parentOrganisationunit = $manager->merge($this->getReference( $parentReference ));
                $organisationunit->setParent($parentOrganisationunit);
                $distinctLongnameAndParent[] = array('longname'=>$humanResourceOrganisationunit['shortname'],'parent'=>$parentOrganisationunit->getId());

                $organisationunitReference = strtolower(str_replace(' ','',$humanResourceOrganisationunit['shortname'])).'-organisationunit';
                $this->addReference($organisationunitReference, $organisationunit);
                $manager->persist($organisationunit);
                // Keep reference index for senquential generation of organisation unit structure
                $this->addToIndexedOrganisationunit($organisationunitReference);
            }else {
                $organisationunitReference = strtolower(str_replace(' ','',$humanResourceOrganisationunit['shortname'])).'-organisationunit';
                $this->addReference($organisationunitReference, $organisationunit);
                $manager->persist($organisationunit);
                // Keep reference index for senquential generation of organisation unit structure
                $this->addToIndexedOrganisationunit($organisationunitReference);
            }
            unset($organisationunit);

            // Randomly populate dispensaries, health centres & hospitals under municipal & district councils

            if(
                strpos($humanResourceOrganisationunit['longname'],'District Council') > 0
                || strpos($humanResourceOrganisationunit['longname'],'Municipal Council') > 0
                || strpos($humanResourceOrganisationunit['longname'],'City Council') > 0
                || strpos($humanResourceOrganisationunit['longname'],'Town Council') > 0 ) {
                $dispensaryCount = rand($this->minDispensaryCount,$this->maxDispensaryCount);
                $healthCentreCount = rand($this->minHealthCentreCount,$this->maxHealthCentreCount);
                $hospitalCount = rand($this->minHospitalCount,$this->maxHospitalCount);

                $parentReference = strtolower(str_replace(' ','',$humanResourceOrganisationunit['shortname'])).'-organisationunit';
                $parentOrganisationunit = $manager->merge($this->getReference( $parentReference ));

                // Populate Dispensaries
                for($dispensaryIncr=0;$dispensaryIncr<$dispensaryCount;$dispensaryIncr++){
                    $dispensary = new Organisationunit();
                    //Kip picking dispensaries randomly until unique reference is found
                    do{
                        $dispensaryKey = array_rand($this->organisationunitNames,1);
                        $dispensaryName = $this->organisationunitNames[$dispensaryKey]." Dispensary";
                        $dispensaryShortname = substr(strtolower(str_replace(' ','',str_replace(' Dispensary','',$dispensaryName))),0,12).substr($parentOrganisationunit->getShortname(),0,5).'dsp';
                        $dispensaryReference = strtolower(str_replace(' ','',$dispensaryShortname.substr($parentOrganisationunit->getShortname(),0,5))).'-organisationunit';
                        $parentorgunitreference=array('longname'=>$dispensaryName,'parent'=>$parentOrganisationunit->getId());
                    }while( $this->hasReference($dispensaryReference) || in_array($parentorgunitreference,$distinctLongnameAndParent) );

                    $dispensary->setCode( $dispensaryShortname );
                    $dispensary->setShortname($dispensaryShortname);
                    $dispensary->setLongname($dispensaryName);
                    $dispensary->setParent($parentOrganisationunit);
                    $dispensary->setActive(true);

                    $this->addReference($dispensaryReference, $dispensary);
                    $distinctLongnameAndParent[] = array('longname'=>$dispensaryName,'parent'=>$parentOrganisationunit->getId());
                    $manager->persist($dispensary);


                    // Populate expected completeness figures for public and private
                    // Enter record for public and private form
                    $formNames=Array('Public Employee Form','Private Employee Form');
                    $form = $manager->getRepository('HrisFormBundle:Form')->findOneBy(array('name'=>$formNames[array_rand($formNames,1)]));

                    $organisationunitCompleteness = new OrganisationunitCompleteness();
                    $organisationunitCompleteness->setOrganisationunit($dispensary);
                    $organisationunitCompleteness->setForm($form);
                    $expectations=Array(2,3,4);
                    $organisationunitCompleteness->setExpectation($expectations[array_rand($expectations,1)]);
                    $manager->persist($organisationunitCompleteness);
                    // Keep reference index for senquential generation of organisation unit structure
                    $this->addToIndexedOrganisationunit($dispensaryReference);
                    $dispensary = NULL;
                    $dispensaryReference = NULL;
                }
                // Populate Health Centre
                for($healthCentreIncr=0;$healthCentreIncr<$healthCentreCount;$healthCentreIncr++){
                    $healthCentre = new Organisationunit();
                    //Kip picking health centres randomly until unique reference is found
                    do{
                        $healthCentreKey = array_rand($this->organisationunitNames,1);
                        $healthCentreName = $this->organisationunitNames[$healthCentreKey]. " Health Centre";
                        $healthCentreShortname = substr(strtolower(str_replace(' ','',str_replace(' Health Centre','',$healthCentreName))),0,12).substr($parentOrganisationunit->getShortname(),0,5).'htc';
                        $healthCentreReference = strtolower(str_replace(' ','',$healthCentreShortname.substr($parentOrganisationunit->getShortname(),0,5))).'-organisationunit';
                        $parentorgunitreference=array('longname'=>$healthCentreName,'parent'=>$parentOrganisationunit->getId());
                    }while( $this->hasReference($healthCentreReference) || in_array($parentorgunitreference,$distinctLongnameAndParent) );

                    $healthCentre->setCode( $healthCentreShortname );
                    $healthCentre->setShortname($healthCentreShortname);
                    $healthCentre->setLongname($healthCentreName);
                    $healthCentre->setParent($parentOrganisationunit);
                    $healthCentre->setActive(true);
                    $this->addReference($healthCentreReference, $healthCentre);
                    $distinctLongnameAndParent[] = array('longname'=>$healthCentreName,'parent'=>$parentOrganisationunit->getId());
                    $manager->persist($healthCentre);

                    // Populate expected completeness figures for public and private
                    // Enter record for public and private form
                    $formNames=Array('Public Employee Form','Private Employee Form');
                    $form = $manager->getRepository('HrisFormBundle:Form')->findOneBy(array('name'=>$formNames[array_rand($formNames,1)]));

                    $organisationunitCompleteness = new OrganisationunitCompleteness();
                    $organisationunitCompleteness->setOrganisationunit($healthCentre);
                    $organisationunitCompleteness->setForm($form);
                    $organisationunitCompleteness->setExpectation(array_rand(array(2,3,4),1));
                    $manager->persist($organisationunitCompleteness);
                    // Keep reference index for senquential generation of organisation unit structure
                    $this->addToIndexedOrganisationunit($healthCentreReference);
                    $healthCentre = NULL;
                    $healthCentreReference = NULL;
                }
                // Populate Hosptial
                for($hospitalIncr=0;$hospitalIncr<$hospitalCount;$hospitalIncr++){
                    $hospital = new Organisationunit();
                    //Kip picking hospitals randomly until unique reference is found
                    do{
                        $hospitalKey = array_rand($this->organisationunitNames,1);
                        $hospitalName = $this->organisationunitNames[$hospitalKey]." Hospital";
                        $hospitalName = str_replace(' 	',' ',str_replace('   ',' ',str_replace('  ',' ',str_replace('\t',' ',$hospitalName))));
                        $hospitalShortname = substr(strtolower(str_replace(' ','',str_replace(' Hospital','',$hospitalName))),0,12).substr($parentOrganisationunit->getShortname(),0,5).'hsp';
                        $hospitalReference = strtolower(str_replace(' ','',$hospitalShortname.substr($parentOrganisationunit->getShortname(),0,5))).'-organisationunit';
                        $parentorgunitreference=array('longname'=>$hospitalName,'parent'=>$parentOrganisationunit->getId());
                    }while( $this->hasReference($hospitalReference) || in_array($parentorgunitreference,$distinctLongnameAndParent) );

                    $hospital->setCode( $hospitalShortname );
                    $hospital->setShortname($hospitalShortname);
                    $hospital->setLongname($hospitalName);
                    $hospital->setParent($parentOrganisationunit);
                    $hospital->setActive(true);
                    $this->addReference($hospitalReference, $hospital);
                    $distinctLongnameAndParent[] = array('longname'=>$hospitalName,'parent'=>$parentOrganisationunit->getId());
                    $manager->persist($hospital);

                    // Populate expected completeness figures for public and private
                    // Enter record for public and private form
                    $formNames=Array('Public Employee Form','Private Employee Form');
                    $form = $manager->getRepository('HrisFormBundle:Form')->findOneBy(array('name'=>$formNames[array_rand($formNames,1)]));

                    $organisationunitCompleteness = new OrganisationunitCompleteness();
                    $organisationunitCompleteness->setOrganisationunit($hospital);
                    $organisationunitCompleteness->setForm($form);
                    $organisationunitCompleteness->setExpectation(array_rand(array(2,3,4),1));
                    $manager->persist($organisationunitCompleteness);

                    // Keep reference index for senquential generation of organisation unit structure
                    $this->addToIndexedOrganisationunit($hospitalReference);
                    $hospital = NULL;
                    $hospitalReference = NULL;
                }
            }
            $parentOrganisationunit = NULL;
		}

        /**
         * Generate organisation unit structure and
         * organisationunit levels.
         */
        // Workound parent reference
        $organisationunitLevelReference = strtolower(str_replace(' ','','Level 1')).'-organisationunitlevel';
        if($this->hasReference($organisationunitLevelReference)) {
            // Get orgunitlevel from reference
            $organisationunitLevel = $this->getReference($organisationunitLevelReference);
        }else {
            // Persist and it's reference
            $organisationunitLevel = new OrganisationunitLevel();
            $organisationunitLevel->setLevel(1);
//            $levelName = 'Level '.$organisationunitLevel->getLevel();
//            if($organisationunitLevel->getLevel()==1) $levelName="Ministry Of Health &SW";
//            $organisationunitLevel->setName($levelName);
            $organisationunitLevel->setName('Level '.$organisationunitLevel->getLevel());
            $this->addReference($organisationunitLevelReference, $organisationunitLevel);
            $manager->persist($organisationunitLevel);
        }
        // Generating organisationunit structure
        if(!empty($this->indexedOrganisationunits)) {
            foreach($this->getIndexedOrganisationunits() as $indexedOrganisationunitKey=>$indexedOrganisationunitReference) {
                $organisationunit = $manager->merge($this->getReference( $indexedOrganisationunitReference ));

                // Populate orgunit structure
                $organisationunitStructure = new OrganisationunitStructure();
                $organisationunitStructure->setOrganisationunit($organisationunit);

                // Figureout level on the structure by parent
                if($organisationunit->getParent() == NULL) {
                    // Use created default first level for organisationunit structure
                    $organisationunitStructure->setLevel( $organisationunitLevel );
                    $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit());
                }else {
                    // Create new orgunit structure based parent structure

                    //Refer to previously created orgunit structure.
                    $parentOrganisationunitStructureReferenceName=strtolower(str_replace(' ','',$organisationunit->getParent()->getShortname())).'-organisationunitstructure';
                    $parentOrganisationunitStructureByReference = $manager->merge($this->getReference( $parentOrganisationunitStructureReferenceName ));

                    // Cross check to see if level is already created for reusability.
                    $currentOrganisationunitLevelname = 'Level '.($parentOrganisationunitStructureByReference->getLevel()->getLevel()+1);

                    if($this->hasReference(strtolower(str_replace(' ','',$currentOrganisationunitLevelname)).'-organisationunitlevel')) {
                        // Reuse existing reference
                        $currentOrganisationunitLevel = $this->getReference(strtolower(str_replace(' ','',$currentOrganisationunitLevelname)).'-organisationunitlevel');
                        $organisationunitLevel = $manager->merge($currentOrganisationunitLevel);
                    }else {
                        // Create new Level and reference.
                        $organisationunitLevel = new OrganisationunitLevel();
//                        $organisationunitLevel->setLevel($levelName);
                        $organisationunitLevel->setLevel($parentOrganisationunitStructureByReference->getLevel()->getLevel()+1);
                        $organisationunitLevel->setName('Level '.$organisationunitLevel->getLevel());
                        //Wild hack to set data entry level
                        if($organisationunitLevel->getLevel() == 4) {
                            $organisationunitLevel->setDataentrylevel(True);
                            $organisationunitLevel->setDescription("Data Entry Level");
                        }

                        $organisationunitLevelReference = strtolower(str_replace(' ','',$organisationunitLevel->getName())).'-organisationunitlevel';
                        $this->addReference($organisationunitLevelReference, $organisationunitLevel);
                        $manager->persist($organisationunitLevel);
                    };

                    // Use reference of created/re-used level
                    $organisationunitStructure->setLevel( $organisationunitLevel );
                    unset($organisationunitLevel);

                    /*
                     * Append Level organisation units based on their parent level.
                     */
                    if($organisationunitStructure->getLevel()->getLevel() == 1) {
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 2) {
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 3) {
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 4) {
                        $organisationunitStructure->setLevel4Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 5) {
                        $organisationunitStructure->setLevel5Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel4Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 6) {
                        $organisationunitStructure->setLevel6Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel5Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel4Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent()->getParent()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent()->getParent()->getParent()->getParent());
                    }
                }
                $organisationunitStructureReference = strtolower(str_replace(' ','',$organisationunit->getShortname())).'-organisationunitstructure';
                $this->addReference($organisationunitStructureReference, $organisationunitStructure);
                $manager->persist($organisationunitStructure);
                unset($organisationunitStructure);
            }
        }
        // Once organisatinounits are in database, assign admin to ministry
        // and district user to one of the districts
        //admin user
        $adminUserByReference = $manager->merge($this->getReference( 'admin-user' ));
        $mohswByReference = $manager->merge($this->getReference( 'mohsw-organisationunit' ));
        $adminUserByReference->setOrganisationunit($mohswByReference);
        $manager->persist($adminUserByReference);
        //district user
        $districtUserByReference = $manager->merge($this->getReference( 'district-user' ));
        $arushadcByReference = $manager->merge($this->getReference( 'arushadc-organisationunit' ));
        $districtUserByReference->setOrganisationunit($arushadcByReference);
        $manager->persist($districtUserByReference);
        //hospital user
        $hospitalUserByReference = $manager->merge($this->getReference( 'hospital-user' ));
        $bugandorefhspByReference = $manager->merge($this->getReference( 'bugandorefhsp-organisationunit' ));
        $hospitalUserByReference->setOrganisationunit($bugandorefhspByReference);
        $manager->persist($hospitalUserByReference);


		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        //LoadFriendlyReport preceeds
		return 7;
        //LoadOrganisationunitGroup follows
	}

}
