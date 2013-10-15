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
namespace Hris\RecordsBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\FormBundle\DataFixtures\ORM\LoadFieldData;
use Hris\FormBundle\DataFixtures\ORM\LoadFormData;
use Hris\FormBundle\DataFixtures\ORM\LoadOrganisationunitData;
use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\FormFieldMember;
use Hris\RecordsBundle\Entity\History;
use Hris\RecordsBundle\Entity\Record;
use Hris\RecordsBundle\Entity\Training;
use Hris\UserBundle\DataFixtures\ORM\LoadUserData;

class LoadRecordData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
    private $maleNames;

    /**
     * @var array;
     */
    private $femaleNames;

    /**
     * @var integer;
     */
    private $recordsPerOrganisationunit;

    /**
     * @var array
     */
    private $courseNames;

    /**
     * @var array
     */
    private $courseLocations;

    /**
     * @var array
     */
    private $sponsor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recordsPerOrganisationunit=4;
    }

    /**
     * Returns array of male name fixtures.
     *
     * @return mixed
     */
    public function getMaleNames()
    {
        return $this->maleNames;
    }

    /**
     * Returns array of female name fixtures.
     *
     * @return mixed
     */
    public function getFemaleNames()
    {
        return $this->femaleNames;
    }

    /**
     * Returns array of coursenames fixtures.
     *
     * @return mixed
     */
    public function getCourseNames()
    {
        return $this->courseNames;
    }

    /**
     * Returns array of course locations fixtures.
     *
     * @return mixed
     */
    public function getCourseLocations()
    {
        return $this->courseLocations;
    }

    /**
     * Returns array of sponsoros fixtures.
     *
     * @return mixed
     */
    public function getSponsors()
    {
        return $this->sponsor;
    }

    /**
     * Randomize outpudate between starting year and ending year.
     * where start and end year range, are group of years, from which random start year
     * will be picked.
     * .e.g randDate(array(10,20),array(5,10))
     *
     * @param $yearStartRange
     * @param $yearEndRange
     * @return bool|string
     */
    public function getRandDate($yearStartRange,$yearEndRange){
        $startDate= date('Y-m-d',strtotime('-'.mt_rand($yearStartRange[0],$yearStartRange[1]).' years'));
        $endDate=date('Y-m-d',strtotime('-'.mt_rand($yearEndRange[0],$yearEndRange[1]).' years'));
        $days = round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
        $n = rand(10,$days);
        return date("Y-m-d",strtotime("$startDate + $n days"));
    }

    /**
     * Returns array of dummy male names
     * @return array
     */
    public function addDummyMaleNmes()
    {
        // Load Public Data
        $this->maleNames = Array(
            'Abdallah','Abdallah','Abdi','Abdu','Abduel','Abdul','Abedi','Abel','Abiudi','Abraham','Abraham','Abubakari','Achieng','Adabu','Adam','Aderson','Adhiambo','Adieli','Adija','Adinani','Adolf','Adolph','Adrian','Adwell','Agres','Ahadiel','Akonaay','Akonay','Akunaay','Akunay','Akuti','Akwanya','Akwilin','Akyoo','Akyoo','Albert','Alberto','Aleck','Alemyo','Alex','Alexander','Alexzandra','Alfani','Alfayo','Alfonce','Alfred','Ali','Ali','Aliamini','Alice','Alloyce','Ally','Ally','Alois','Aloyce','Aloyce','Aloysius','Alphonce','Alphonce','Alyce','Ama','Amath','Ambroce','Ambros','Ambrose','Ambrosi','Amedius','Ami','Aminiel','Aminiel','Amir','Amiri','Amlima','Amma','Ammi','Amnaay','Amon','Amos','Amos','Amosi','Amrose','Amsi','Anaeli','Anaely','Anaely','Anandumi','Anderson','Anderson','Andrea','Andrea','Andrew','Andrew','Andy','Angelimu','Angiresi','Angumbikwe','Annacletius','Anney','Anney','Anselim','Anselmi','Anta','Antanamsu','Anthony','Anthony','Anton','Antoni','Antony','Aonali','Apaeli','Apolinary','Apollo','Arajiga','Areray','Arnold','Aron','Asantiel','Aseri','Asheri','Assenga','Assey','Astofeli','Aston','Athanas','Atheniel','Athman','Athuman','Athumani','Athumani','Atieno','August','Augustine','Augustino','Augustino','Awadhi','Awaki','Awaki','Axwesso','Axwesso','Ayo','Ayoub','Azayo','Baasa','Babu','Baghari','Baha','Baha','Bahati','Bahati','Bakari','Bakari','Bakary','Baltazar','Baltazary','Balua','Barae','Baran','Bararukuliliza','Barie','Bariki','Barnabas','Basaya','Batega','Batson','Batti','Bayai','Baynit','Bayo','Bayona','Bazil','Beda','Bejumla','Benangodi','Benard','Bendera','Benedict','Benedicty','Benela','Benezeth','Benjamin','Benjamini','Benson','Benta','Beraa','Bernard','Bernard','Bethuel','Bethuel','Betueli','Bilali','Bilia','Billa','Billidad','Binyondo','Bisoleka','Boa','Boamo','Boaz','Boi','Boma','Bomani','Bon','Boniface','Boniface','Bonifasi','Bonifasi','Bonifatio','Boniphace','Boniphace','Bshiri','Budenu','Bujiku','Bukhay','Buku','Bunoge','Bura','Burra','Burton','Buya','Bwanga','Bwire','Bwire','Byenobi','Byneti','Carmichael','Casmiri','Casmiry','Casmiry','Casper','Cassian','Cassius','Celestine','Chabonde','Chacha','Chacha','Chacky','Chailla','Challe','Challo','Chambole','Chami','Chami','Champanda','Chamwela','Chande','Chandoo','Charaani','Charles','Charles','Charlesi','Charos','Chasuka','Cheche','Chedego','Chedego','Cheleji','Chibago','Chikoti','Chimbongo','Chitenje','Chonya','Christian','Christopher','Christopher','Chuwa','Clement','Cleophas','Cletus','Cornel','Cosmas','Costatin','Daffi','Dafi','Dagharo','Dahaa','Dahaye','Daimon','Dalan','Damas','Damian','Danga','Daniel','Daniel','Daniely','Darabe','Darvoi','Dastan','Daud','Daudi','Daudi','Dauglaus','David','David','Dawson','Deemay','Denis','Denis','Deogratias','Deogratius','Desdery','Desdery','Deus','Didas','Diday','Didiah','Dionice','Dominic','Dominick','Donald','Dukho','Dulle','Duwe','Duxo','Ebenezer','Eden','Edna','Edward','Edward','Edwin','Efrahim','Efrahimu','Efrem','Ekwabi','Elaston','Eldorick','Elia','Eliah','Eliahu','Eliakimu','Eliamini','Eliamini','Eliapenda','Eliard','Elias','Elias','Elibariki','Elichilia','Elieza','Elifadhili','Elifas','Elifasi','Elihuruma luka','Elinahema','Elingaya','Elinisa','Eliphas','Elipokea','Elirehema','Elisa','Elisamia','Elisauti','Elius','Eliyuko','Elly','Emanuel','Emanuel','Emanueli','Emerson','Emily','Emmanuel','Emmanuel','Emmanueli','Emmay','Enezael','Engino','Ephrahim','Ephraim','Epimaki','Erasto','Erasto','Ermesti','Ernest','Ernest','Esali','Esau','Eshery','Esto','Estomihi','Eugeni','Euinse','Eusebi','Eusebious','Evo','Evod','Exavier','Ezekiel','Ezekieli','Fabian','Fadhili','Fanuel','Fanuel','Fatael','Faustin','Faustine','Fedrick','Feksi','Felisi','Felix','Felix','Ferdinand','Festy','Fidelis','Filbert','Fissoo','Flugence','Focas','Focus','Francis','Francis','Francs','Frank','Frank','Fransi','Fransics','Fredinandes','Fredrick','Fredrick','Fredy','Frimini','Fungameza','Fungo','Gaare','Gabriel','Gabriel','Gadiye','Gallus','Gamaeli','Gara','Gasper','Gasper','Gaudence','Gayu','Geffi','Gela','Gembe','Gemuwang`','Geodfrey','Geofrey','George','George','Gerald','Gerson','Gervas','Gervasi','Ghamung','Gibaseya','Gibaseya','Gidadi','Gidahabu','Gidawe','Gideon','Gidion','Gilbert','Gisa','Gisangina','Gitagan','Gitagno','Gitambo','Godfrey','Godfrey','Godlove','Godrick','Godson','Godwin','Goha','Greyson','Gurtu','Gurty','Gustaph','Gwaha','Gyubi','Haali','Haalu','Haima','Haji','Hakira','Hallo','Hallo','Hamadi','Hambati','Hamidu','Hamis','Hamisi','Hamnae','Harmi','Hasan','Hasani','Hashimu','Hassan','Hassan','Hatibu','Haymatia','Hemed','Hendrick','Hendry','Henga','Henrick','Henry','Heriel','Herman','Hermes','Herri','Hezekiel','Hezron','Hhayduru','Highness','Hilary','Hintai','Hipolite','Hizza','Hombo','Hongo','Honorath','Hosea','Huberi','Hubert','Hugho','Hugho','Hussein','Ibrahim','Ibrahim','Ibrahimu','Idas','Idd','Iddi','Iddi','Idowa','Idrissa','Ignace','Ignance','Ignatus','Ijumaa','Ikwabe','Inyasi','Irafay','Irira','Isaack','Isaay','Isaay','Isack','Isack','Isaka','Isangya','Isaya','Isere','Ismail','Ismail','Israely','Issa','Issa','Issaay','Issac','Issah','Issangya','Issay','Isumail','Itambu','Jabiri','Jack','Jackob','Jackson','Jackson','Jacob','Jacob','Jaffar','Jamberi','James','James','Jamesi','Japhet','Japhet','Jastin','Jefta','Jeremiah','Jeremiah','Jerome','Joachim','Joachim','Joakim','Jocktan','Joel','Joel','Joeli','John','John','Johnson','Jonas','Jonas','Jonathan','Jonathani','Josam','Josaphati','Josep','Joseph','Joseph','Josephat','Josephat','Joshua','Jotam','Jotham','Judith','Juliasi','Julius','Juma','Juma','Jumah','Jumanne','Jume','Justin','Justine','Justine','Kaaya','Kaaya','Kababa','Kabaka','Kabarega','Kabarila','Kabipe','Kabulakyage','Kabura-kimogera','Kachungu','Kadeso','Kaduma','Kafugugu','Kafuku','Kagambo','Kahema','Kahise','Kahwa','Kaiser','Kalay','Kalega','Kalelo','Kalisti','Kalongo','Kaluse','Kamazima','Kambanga','Kamtwela','Kamwana','Kanaeli','Kanana','Kang"iria','Kanota','Kanusya','Kanyere','Kanyika','Kapara','Kapaya','Karani','Karata','Karengi','Kariagi','Kariati','Karibueli','Karuga','Kasasala','Kashe','Kasmir','Kasoka','Kassim','Kastuli','Kasumuni','Katama','Katete','Katojo','Katumbi','Kauzen','Kavishe','Kavumo','Kavuraya','Kawa','Kawishe','Kayamba','Kayanda','Kayoka','Kazoba','Ken','Kengia','Kente','Kerea','Kereto','Kessy','Khaday','Khalifa','Khamisi','Khan','Khanje','Khatib','Khatibu','Khatibu','Khotwe','Kiama','Kiangi','Kiango','Kibira','Kibona','Kibuga','Kichao','Kidaya','Kiduma','Kihara','Kihedu','Kikaho','Kikaho','Kikasi','Kikwale','Kikwasi','Kilawe','Kileo','Kileo','Kilinga','Kilion','Killbard','Kimako','Kimambo','Kimambo','Kimario','Kimaro','Kimaro','Kimaryo','Kimath','Kimathi','Kimei','Kimiri','Kimuya','Kimweri','Kinasha','Kingob','Kingu','Kiondo','Kiondo','Kipara','Kipimo','Kiravu','Kireku','Kiria','Kiriama','Kiringa','Kiromo','Kirway','Kisabo','Kisaka','Kisaka','Kisakisa','Kishe','Kisila','Kisioki','Kisiri','Kissima','Kisuda','Kita','Kitambi','Kitangoi','Kitangwa','Kitika','Kitoi','Kitomari','Kitomary','Kittu','Kitundu','Kitunga','Kitururu','Kitutu','Kiula','Kiula','Kivambe','Kivumba','Kivuyo','Kivuyo','Kiwale','Kiwanga','Kiwayo','Kiwia','Kleruu','Kodi','Koikai','Koillah','Koira','Kola','Komba','Komba','Kombe','Kombo','Kombwa','Korei','Korio','Koris','Krillo','Kuboja','Kuiga','Kujan','Kulwa','Kundael','Kundael','Kundasa','Kupaza','Kututu','Kuwe','Kuyenga','Kwaang','Kwaslema','Kweka','Kyande','Kyara','Kyarua','Kyungai','Laanoi','Laanyuni','Ladislaus','Lage','Laiser','Laitayo','Laizer','Laizer','Landei','Langay','Lange','Langoi','Lantiankira','Lapokye','Lapya','Lasway','Laton','Laurent','Lauri','Lawi','Lawrance','Lawrence','Laya','Lazaro','Lazaro','Lebabu','Leindoi','Leiyo','Lekasio','Lekeni','Lekidung`i','Lekoringo','Lekule','Lekundayo','Lelo','Lema','Lema','Lembinyo','Lembris','Lenasira','Lengine','Lenina','Leo','Leo','Leon','Leonard','Leonard','Leonce','Levery','Levurko','Lewi','Liberaty','Likinderaki','Likunama','Lissu','Liteleko','Liwamba','Lobikoki','Logiru','Loi','Loilang`akaki','Loishorwa','Loivotoki','Lokaji','Lokiri','Lomayan','Lomnyaki','Longida','Longisa','Longorori','Lonjini','Lonyamali','Lordman','Lorri','Losaru','Loshorwa','Losioki','Losioki','Lotaanywaki','Lotasarwaki','Loth','Lotha','Lothi','Loy','Loya','Lubida','Lucas','Lucas','Lucumay','Lucumay','Ludovicky','Lugano','Lugendo','Lugendo','Luka','Lukas','Lukindo','Lukumay','Lukumay','Lukwaro','Lulu','Lupasa','Lusigariye','Lusubilo','Lusunike','Luthonda','Lwesya','Lyakurwa','Lyamuya','Lyapa','Lyari','Lyaruu','Lyatuu','Lyimo','Lyimo','Lyimu','Lyinga','Maafa','Maamba','Maasa','Mabala','Mabela','Mabeyo','Macha','Macha','Machuki','Machumani','Machuwa','Mackfason','Madangi','Madimilo','Madungare','Maeda','Maeri','Mafie','Mafita','Mafoe','Mafole','Magalya','Maganga','Maganya','Magendela','Magere','Maggo','Maghasa','Magnus','Magohe','Magongo','Maguluko','Magunda','Magway','Mahamoudu','Mahanyu','Mahela','Mahize','Mahmoud','Maimu','Makabali','Makalla','Makange','Makembe','Makimario','Makiya','Makoi','Makono','Makori','Makori','Makule','Makundi','Makundi','Makunga','Makungu','Makungu','Malando','Malegeya','Malifedha','Malimi','Malisa','Malley','Malley','Mallya','Malulu','Malya','Mamboya','Mammba','Mamtare','Manase','Manase','Manda','Manda','Manga','Manga','Mangara','Mangare','Manjala','Manoni','Manyaka','Manyama','Manyama','Mao','Mapala','Mapenzi','Mapunda','Maqway','Marandu','Marcel','Marcel','Marco','Marco','Maregeri','Margwe','Mariba','Mariki','Mariwa','Marki','Marko','Maro','Martin','Martine','Masago','Masaka','Masaki','Masambuli','Masamu','Masanja','Masao','Masasila','Masawe','Masengo','Mashaeli','Mashaka','Mashala','Mashambo','Mashauri','Mashayo','Mashika','Masika','Masimba','Masini','Masoud','Masowa','Massae','Massago','Massao','Massawa','Massawe','Massawe','Massay','Massoud','Massu','Massud','Matalila','Matata','Matawa','Matee','Matela','Matemu','Materu','Mathayo','Mathayo','Mathayo','Mathew','Mathew','Mathias','Matias','Matina','Matinde','Matinya','Matiya','Matle','Matoi','Matoke','Matowo','Matulo','Matundwe','Matunga','Maturo','Mauki','Maulid','Mauma','Mavura','Mawa kulewa','Mawangala','Maxon','Mayengo','Mazalla','Mazengo','Mazige','Mazumba','Mbahi','Mbando','Mbarouk','Mbasha','Mbasha','Mbayani','Mbazi','Mbazi','Mberesero','Mbesere','Mbeyale','Mbinda','Mbise','Mbise','Mbonde','Mbonea','Mbonea','Mbora','Mbotta','Mboya','Mbughuni','Mbuki','Mbulanila','Mbunda','Mbuya','Mbuya','Mbwambo','Mbwambo','Mbwana','Mbwasi','Mbwilo','Mcha','Mcharo','Mcharo','Mchau','Mchau','Mchelo','Mchilla','Mchomvu','Mchuwa','Mdavire','Mdee','Mdeo','Mdoe','Mduma','Medadi','Medard','Medardi','Meeki','Meela','Meena','Meena','Megeme','Meitekinyi','Meiya','Melami','Melari','Meliara','Melita','Melkiory','Melkizedeki','Mella','Memruti','Menavi','Mende','Mengi','Menradi','Menyisia jacob','Merishy','Merus','Mesarieki','Meseiyeki','Mesikongi','Mesiokini','Metalami','Methew','Method','Method','Meveriki','Mfala','Mfangavo','Mfangavo','Mfinanga','Mgalula','Mganga','Mgaza','Mgero','Mgombele','Mgongo','Mgonhu','Mgonja','Mgulu','Mhamed','Mhando','Mhina','Mhoja','Mhunga','Michael','Michael','Midlaster','Migunga','Mika','Minja','Minja','Miraji','Mirau','Mirrudy','Mirry','Misanya','Misungwi','Mitokuwani','Mjema','Mjema','Mjenja','Mkalagalleh','Mkama','Mkamba','Mkatale','Mkela','Mkenda','Mkende','Mkengefu','Mkeni','Mkerenga','Mkina','Mkinga','Mkini','Mkiramweni','Mkony','Mkonyi','Mkufya','Mkuki','Mkumba','Mkuna','Mkundai','Mkundi','Mkwanda','Mkwe','Mlaki','Mlambo','Mlau','Mlay','Mlay','Mlemeta','Mlimasunzu','Mlondwa','Mlowezi','Mlugu','Mlunga','Mmari','Mmari','Mmassy','Mmbaga','Mmbali','Mmbando','Mmbando','Mmbugu','Mmuni','Mnamosi','Mndeme','Mndeme','Mnembo','Mngulu','Mngulwi','Mnyangamila','Mnyanghali','Mnyawi','Mnzava','Mnzava','Mohamed','Mohamed','Mohamedi','Moi','Moi','Moki','Molango','Molel','Moles','Molle','Mollel','Mollel','Mollelson','Mollely','Mombury','Mombury','Monah','Mophat','Mori','Moruo','Moses','Moses','Mosha','Mosha','Moshi','Mossalo','Mosses','Mpeku','Mpera','Mpimo','Mpingwa','Mpondo','Mramba','Mrema','Mrema','Mremi','Mrimi','Mrina','Mrina','Mrindoko','Mringo','Mritta','Mrosso','Mruma','Mrutu','Mrutu','Msae','Msaga','Msaki','Msaki','Msalanga','Msallu','Msambazi','Msami','Msangi','Msangi','Msanya','Msechu','Mselle','Msemakweli','Msemo','Msemo','Mshana','Mshana','Mshanga','Msilanga','Msisi','Msisi','Msofe','Msolla','Msoma','Msovu','Msumari','Msumari','Msumary','Msuya','Msuya','Mtango','Mtaturu','Mtega','Mtei','Mtei','Mtenga','Mtenga','Mtengwa','Mtibua','Mtili','Mtiti','Mtiti','Mtitu','Mtoni','Mtui','Mtui','Mtuka','Mtunguja','Mtuy','Muhale','Muhale','Muhidini','Mujwavzi','Mukandara','Mulda','Mumbee','Mumbuli','Muna','Mundhe','Munga','Mungaba','Mungaya','Mungure','Mungure','Munishi','Munishi','Munisi','Munisi','Munkyala','Munna','Munnanga','Munua','Mununa','Munuo','Munuo','Muro','Murondoro','Murray','Murtadha','Musa','Musa','Museru','Mushi','Mushi','Mushigwa','Muslim','Mussa','Mutashobya','Muteriali','Muya','Muyengi','Muyobera','Mvamila','Mvungi','Mvunta','Mwaijaga','Mwaimu','Mwakanjila','Mwakanyemba','Mwamafupa','Mwambully','Mwami','Mwampeta','Mwanahenge','Mwanayongo','Mwandende','Mwanga','Mwanga','Mwangomale','Mwanja','Mwanyombole','Mwaruanda','Mwasha','Mwasu','Mwembago','Mwenda','Mwendesha','Mwendikes','Mwendwa','Mwene','Mweneyechande','Mwenyechande','Mweta','Mwigamba','Mwikabe','Mwinyimweni','Mwita','Mzava','Mzee','Mzee','Mziray','Nada','Nada','Naftal','Naigara','Naiman','Nairobi','Naisosion','Nalogwa','Naman','Namsemba','Nanga','Nangay','Nangoro','Nangulale','Nanowa','Nanyanje','Nanyaro','Nanyor','Nasari','Nassary','Nassary','Natai','Natalis','Natay','Natema','Nathan','Nathanael','Nathanaeli','Nchama','Nchimbi','Ndahani','Ndahani','Ndaki','Ndalo','Ndaskoi','Ndatene','Ndege','Ndeki','Ndeliso','Ndemfoo','Nderingiyo','Ndetaulwa','Ndewario','Ndeyosi','Ndimubenya','Ndiwabu','Ndoipo','Ndomba','Ndosi','Ndossa','Ndossi','Ndosy','Ndumbalo','Ndunguru','Ndyetabula','Nelson','Nemes','Neville','Newanga','Ng`eleshi','Ng`imba','Ng`itu','Ng`weshemi','Ngailo','Ngalali','Ngalesoni','Ngalika','Nganga','Ngao','Ngasa','Ngatunga','Ngerangera','Nghebi','Ngilorit','Ngiloriti','Ngira','Ngiriki','Ngoka','Ngoka','Ngoly','Ngoma','Ngongi','Ngora','Ngorkoni','Ngowi','Ngowi','Nguma','Ngumila','Nguno','Ngunyi','Ngurumwa','Ngurumwa','Nicholaus','Nicodemas','Nicolaus','Njama','Njau','Njiku','Njile','Njuu','Nkamba','Nkii','Nkika','Nkini','Nkoha','Nkomola','Nkorisa','Nkwama','Nkya','Nkya','Nnko','Nnko','Nnkya','Nnkya','Noel','Noelly','Nsimba','Nsubile','Ntaho','Ntigiri','Ntogwisangu','Ntulwe','Ntwanga','Nyaboke','Nyachibongo','Nyachisamwa','Nyaindi','Nyaki','Nyakwela','Nyambura daudi','Nyamwera','Nyanda','Nyanda','Nyange','Nyaurah','Nyerere','Nyoni','Nzaro','Nzenje','Nzowa','Obadia','Obed','Obedi','Obedi','Obeid','Obilio','Ogungu','Ojala','Okith','Oldian','Ole','Olembile','Olenapirura','Olesitoi','Olloithay','Olomi','Olotu','Olvery','Omari','Omary','Omary','Ombay','Ombeni','Omeme','Onel','Onesmo','Ongugu','Oresto','Orrest','Osima','Osurani','Ottaru','Owawa','Pailo','Pallangyo','Pallangyo','Pamba','Pancrace','Pancras','Panga','Panga','Panga','Pantaleo','Parangyo','Paresso','Pascal','Pascal','Paschal','Pasian','Pata','Patrick','Paul','Paul','Pauli','Paulo','Paulo','Pella','Pendael','Peter','Peter','Peter','Phanueli','Pharesi','Philbert','Philemon','Philemon','Philip','Philipo','Philipo','Phissoo','Phlipo','Pissa','Pius','Pius','Plasius','Possa','Prosper','Pyuza','Qaduwe','Qamara','Qamunga','Qaresi','Qaymo','Qwaray','Qwaye','Rabeck','Rabieth','Rabson','Ragita','Rajab','Rajabu','Ramadhani','Ramadhani','Raphael','Raphael','Rashid','Rayani','Raymond','Remen','Reuben','Richard','Richard','Ringo','Ringson','Rishandumi','Rithi','Ritoine','Robert','Robert','Robinson','Ruben','Rukupwa','Rutabingwa','Rutakangwa','Rutatinisibwa','Rutto','Rwagatenga','Ryoba','Sabdiel','Sablack','Sadala','Sadiki','Safari','Safari','Safari hhary','Safiel','Saiboku','Saibull','Said','Said','Saidi','Saiguran','Sailevu','Saisa','Saitikoki','Sakita','Salaho','Salanga','Saliezi','Salija','Salimu','Salum','Salum','Sambaya','Samdella','Samson','Samuel','Samuel','Samwel','Samwel','Samweli','Samweli','Saneti','Sanga','Sanga','Sango','Sangu','Saningo','Saningo','Sanka','Sanka','Sarangu/ nanagi','Sareyo','Sareyo','Sarga','Saria','Sarmet','Saro','Saronga','Saroni','Sarun','Sarushoy','Sasiyo','Saul','Saule','Sawaki','Sebastian','Sebastian','Sedute','Seif','Seiyai','Seiyai','Seiyai','Sekidio','Seleman','Selemani','Selenyai','Selestin','Semba','Sembe','Semkunde','Semndeli','Sendeka','Sengael','Sengasu','Senge','Senkoro','Senya','Senyeiye','Senzighe','Senzighe','Senzota','Sephania','Seremon','Seth','Severine','Sewando','Shaban','Shaban','Shabani','Shabani','Shaib','Shalua','Shami','Shanalingigwa','Shangai','Shani','Shao','Shariff','Shatumai','Shauri','Shauri','Shayo','Shayo','Shedrack','Sheheson','Shekivuli','Shelerue','Shemaghembe','Shemaya','Shemng`ombe','Shemzigwa','Shenga','Sheshe','Shikengunden','Shilogela','Shio','Shirima','Shirima','Shishi','Shitrael','Shiyo','Shungu','Shuwaka','Siafu','Siame','Sianga','Siasi','Sichome','Sifaeli','Sifamen','Sigera','Sikawa','Silayo','Silimika','Silvano','Silvester','Sima','Simba','Simbo','Simfukwe','Simon','Simon','Simpa','Sindole','Singano','Sinodya','Siphuely','Sirikwa','Sirili','Sitoya siria','Siwandet','Siwandeti','Sizya','Slaa','Slayo','Soka','Solas','Solomon','Somi','Songoro','Sospeter','Sosthenes','Sozi','Stalson','Stanley','Stansilaus','Stanslaus','Steer','Stefano','Stephano','Stephen','Stephin','Steven','Steven','Stewart','Stuart','Suage','Sufian','Sukari','Sukuma','Suleiman','Suleiman','Suleman','Suleyman','Sulle','Sulley','Sultan','Sumari','Sumary','Sumayan','Sumuni','Sung`are','Sungi','Sungura','Sungura','Supuk','Surumbu','Swai','Swai','Swalehe','Swedi','Sweya','Syaiti','Sylvanous','Sylvester','Sylvester','Syrivery','Tabuse','Tahani','Tajieli','Tarimo','Tarimo','Tarmo','Tassa','Teeka','Tegamaisho','Tella','Temael','Temba','Temba','Temu','Temu','Tenga','Tenga','Teophil','Teretio','Teri','Terry','Tesha','Tesha','Thadeo','Thadeus','Thani','Theobald','Theodore','Theodory','Theoduli','Theonasi','Thobias','Thobiasi','Thomas','Thomas','Thomas','Tibenda','Tillya','Tilya','Timothy','Timothy','Tingo','Tippe','Tiringa','Tito','Tito','Tlemai','Tluway','Toronge','Tsaghara','Tsaxara','Tulu','Tuwati','Twalbu','Ufoo','Uhwelo','Uiso','Umbe','Ummbe','Uo','Upendo','Urasa','Urassa','Urio','Urioh','Uromi','Uronu','Usallu','Uwingabile','Valentine','Valerian','Valerian','Valeriani','Vanica','Vario','Veana','Venance','Venance','Vicent','Victor','Victoroius','Vincent','Vitalis','Vitaly','Vitus','Vumilia-didas','Wado','Wagili','Walter','Wambura','Wambura','Wanjara','Wanjara','Wanje','Warucha','Washan','Wasia','Waziri','Wekesa','Wema','Wilbert','Wilfred','Wilfred','Wiliamson','William','William','William','Williamu','Willium','Wilson','Wilson','Winfrid','Winno','Wisso','Wlliam','Yakubu','Yanai','Yasini','Yassin','Yesaya','Yobu','Yohana','Yohana','Yona','Yona','Yonas','Yonazi','Yunus','Yunusa','Yusuf','Yusufu','Yusuph','Yusuph','Zablon','Zabron','Zacharia','Zacharia','Zadock','Zakaria','Zakaria','Zakaria','Zakayo','Zakayo','Zakeli','Zebedayo','Zephania','Zephania','Zoya','Zuberi'
        );
        return $this->maleNames;
    }

    /**
     * Returns array of dummy female names
     * @return array
     */
    public function addDummyFemaleNames()
    {
        $this->femaleNames = Array(
            'Angela','Chonge','Peres','Elishiwaria','Genofeva','Joyce','Jane','Omega','Marthina','Bernadeta','Sophia','Ester','Salome','Zuhura','Eileen','Benedicta','Tumaini','Rahma','Magreth','Lucy','Sarah','Clara','Elizabeth','Neema','Lidya','Fatuma','Lina','Endavukai','Elievera','Emmanuela','Julieth','Patricia','Yolenta','Esther','Yukunda','Elifrida','Anna','Getrude','Pendo','Abeda','Immaculata','Millen','Esteria','Aurelia','Ikaanami','Theresia','Margaret','Juliana','Twilumba','Doreen','Rosemary','Vones','Noela','Lilian','Mariam','Eva','Flora','Happyness','Denista','Mary','Veronica','Latifa','Ernesta','Zakia','Regina','Tarsila','Justina','Erther','Zainab','Kaanael','Verynice','Irene','Grace','Scolastica','Veronica','Haika','Eliaichi','Rose','Janeth','Eunice','Ndeshi','Henrica','Emerinsiana','Cesilia','Selina','Hellen','Maryam','John','Hadija','Rashma','Jaquiline','Sixta','Sabina','Agatha','Cecilia','Pamela','Mariana','Modester','Victoria','Eliatirisha','Aceline','Nemburis','Catherine','Marystella','Eliza','Agness','Zarafa','Consolata','Frida','Florence','Restituta','Beatrice','Donata','Fildorin','Naghuiwa','Nancy','Gaudensia','Edina','Edna-joy','Valeriana','Melania','Martha','Naisiriri','Idda','Jescasia','Mwanahamisi','Belinda','Elika','Edith','Bertha','Magdalena','Munde','Elisaria','Shubila','Mwanahawa','Namsimbaeli','Julitha','Notibruga','Josephine','Elder','Abella','Calmen','Ruth','Nipael','Angolwisye','Carol','Praxedis','Asinath','Helena','Mlejo','Mwivei','Ansila','Martine','Glady','Reinia','Emeritha','Agnes','Emeritha','Apaifura','Janeth','Gladness','Clara','Adelina','Furaha','Easther','Furaha','Emannuela','Fatuma','Ritha','Pilly','Fausta','Rehema','Ritha','Adelaide','Joyce','Prolimina','Dinna','Nailejileji','Anna','Bahati','Carolina','Caritas','Asha','Leonia','Sophia','Amina','Fausta','Monica','Bertila','Rehema','Marietha','Prisca','Tausi','Mwanaidi','Rhoda','Halima','Balbina','Bahatiel','Naangai','Husna','Sauda','Albina','Filomene','Mwajabu','Azinael','Astheria','Fabiola','Aloycia','Adriana','Elipendo','Naserian','Euphrasia','Yuster','Dorothy','Kibibi','Palethy','Sr agripina','Basila','Lazaro','Jenifa','Paulina','Macrina','Remilda','Saada','Yohana','Mercy','Clementina','Emanuela','Adiliel','Jekabeth','Anastazia','Emilian','Coletha','Zainabu','Sarafina','Judith','Lightness','Matrona','Wema','Loveness','Hawa','Nakijwa','Levina','Hilda','Epifania','Helen','Denibora','Magreth','Maimuna','Sibilina','Mary','Magdalena','Scollastika','Christina','Eliatirisha','Betycia','Mwatumu','Basilisa','Edna','Bernadeta','Anne','Jeska','Emmy','Bupe','Ledy','Milka','Zainabu','Onderi','Lydia','Grace','Amina','Nora','Asha','Rozina','Hadija','Selina','Flora','Stella','Latipha','Emiliana','Elisaria','Bisati','Rahel','Sophia','Nengarivo','Brenda','Esther','Jamila','Upendo','Juliana','Kurwa','Paulina','Anajoy','Betty','Mercy','Josephine','Safina','Jonaice','Mwanaidi','Dorcas','Jane','Segolena','Raheli','Pauline','Habiba','Edina','Flaviana','Pili','Star','Generose','Elly','Beatrice','Monica','Hellen','Salome','Toba','Lyidia','Miriam','Nolamali','Salpina','Agness','Sabena','Debora','Sharifa','Bahati','Irene','Lilian','Felister','Anisia','Tusekile','Jubilate','Velura','Debora','Vivian','Diana','Dayness','Restituta','Evaresta','Martha','Sada','Sylvana','Aichi','Elinati','Oliva','Witness','Agatha','Zaina','Nemburis','Kadada','Makrina','Aziza','Nembris','Adolfina','Ester','Merry','Blandina','Evaline','Damary','Alonica','Nitujaeli','Shukurani','Rabinsia','Victoria','Rudia','Helen','Suzan','Elizabeth','Kilie','Imakulata','Bibiana','Endaeli','Florah','Christine','Neema','Ambrosia','Penueli','Frida','Devotha','Zuena','Getrude','Nailejileji','Eugenia','Huruma','Lightness','Jeniva','Marry','Hertha','Eliamani','Tina','Hulda','Violet','Rose','Penina','Mwangaza','Mina','Consolata','Veronica','Jema','Ngiishokeny','Butolwa','Leah','Rebecca','Hawa','Emelda','Agripina','Denikyada','Edda','Shivaline','Chausiku','Reiness','Irimina','Lucy','Vusynkilo','Claudia','Belinda','Aurelia','Narea','Elywayda','Saumu','Natangamwaki','Oliver','Germana','Judith','Philemona','Zipporah','Judika','Itikisaeli','Aisha','Ndevera','Isdora','Mwajabu','Akunda','Oliva','Lootosim','Asia','Truefine','Leah','Augustina','Kadogo','Jullyana','Margareth','Daphrose','Della','Mikelina','Hidaya','Itikisael','Ndeonika','Diana','Juweria','Haikael','Emiliana','Marietha','Willhelmina','Elieleka','Menrada','Naomi','Sikudhani','Dominica','Angel','Haines','Suzana','Happy','Mwanne','Generoza','Gladness','Christina','Pulkeria','Ruth','Huruma','Bitial','Benadeta','Renath','Sikuzani','Tulizo','Gloria','Laity','Rosemary','Florentina','Anne','Rahel','Eliafura','Ndeningwambora','Tasimbora','Lucresia','Paschalina','Corona','Felister','Mwajuma','Rhoda','Nabulu','Assumpta','Florencia','Doroth','Rita','Kester','Oliver','Kijakazi','Minza','Agnes','Aurea','Hamida','Mahoza','Loyce','Zaina','Fredricka','Nipanema','Phillipina','Anjela','Mansueta','Marieta','Mwamini','Akwilina','Agripina','Maclean','Domina','Anande','Dearness','Herbetha','Devotha','Marthae','Mariayusta','Ndeaisa','Moshi','Evangeline','Editha','Zena','Rukia','Fortunata','Eliwangu','Aminisia','Jesca','Eutropia','Sekunda','Nenduvoto','Fortunatha','Paris','Lucky','Mkunde','Flotea','Daria','Madina','Penina','Ernester','Anna-theresia','Hellena','Fatema','Mesiaki','Pendaeli','Nembris','Kipengere','Bernadetha','Maria','Jackline','Sabina','Mwivano','Josephine','Clementina','Natihaika','Rebeka','Natihaika','Honoratha','Getruda','Mwajuma','Tuni','Innocensia','Elisimba','Jacqueline','Niwael','Leticia','Mwanaisha','Allen','Prediganda','Zaituni','Emma','Mildred','Matrona','Happiness','Epiphania','Kabula','Annacleta','Idda','Happness','Anniciata','Camerina','Eunice','Abigaeli','Ndekundio','Rodina','Assia','Sigmanta','Dorice','Swaiba','Efrata','Winiasia','Ndeni','Elice','Alice','Matilda','Eliasenya','Elishikarisa','Zahara','Dora','Severa','Angella','Justar','Geogina','Chiwa','Janety','Rukia','Simon','Aisa','Hulda','Kesther','Nathalia','Foibe','Mkashuhuda','Adimila','Leokadia','Nasinyare','Claudia','Benedictor','Rebeka','Colina','Habiba','Dorica','Yusta','Matulo','Atuganile','Emerenciana','Selestina','Clementia','Stella','Evaline','Lucia','Hellena','Isarya','Eamela','Helena','Genoveva','Wingwela','Sekela','Fauster','Bilha','Benta','Sharifa','Josephina','Tatu','Esupat','Fatina','Mahija','Ahimidiwe','Akwelina','Erene','Aingiki','Scholastica','Beatha','Perice','Mwajuma','Ramla','Shamila','Eveter','Jasmin','Payanai','Evelyn','Imani','Anjelista','Aneth','Muji','Evarista','Petronila','Rachel','Fatma','Ndolichimpa','Highness','Elivera'
        );
        return $this->femaleNames;
    }

    /**
     * Returns array of dummy course names
     * @return array
     */
    public function addDummyCourseNames()
    {
        // Load Public Data
        $this->courseNames = Array(
            'Advanced adherence counselling','Computer applications','HIV/AIDS care & treatment','Management of HIV/AIDS','Adolescent reproducctive health','Advanced quality improvement','Advance trauma life support','Clinical skills training','Collaborative tb/HIV activities','Commodities management','Quality improvement','Corruption and ethics infrastructure','Counseling & testing','Data base management','Demographic health survey','Diabetes management','Disaster management','District health  management','Driving certificate','Drug inspection','Early infant diagnosis','Electronic data base','Emergency oral care','Human resource mnagement','Icu management','Rotavirus and pneumonia vaccine ','Management of obstetrict fistula','Management of stds','Management of tanzania fp and AIDS logistics systems','Maralia rapid diagnostic test','Maralia  parasites identification.','Maternal mortality reduction','Medical attendant course','Mentorship on HIV/AIDS','Midwifery child health','Monitoring and evaluation','Mother and child health care','Motor vehicle maintanance','Mtuha ( HMIS )','Multi drug resistance','National HIV care and treatment','Nursing care of malaria  patients','Ophthalmical asstistant','Orientation of service providers on tracer medicines','Personal management skills','Quality assurance and assessment','Quality improvement for HIV/AIDS','Rapid syphilis testing','Record management','Reproductive and child health','Rhmt plannig and reporting'
        );
        return $this->maleNames;
    }

    /**
     * Returns array of dummy course locations
     * @return array
     */
    public function addDummyCourseLocations()
    {
        // Load Public Data
        $this->courseLocations = Array(
            'Arusha, Arumeru','Arusha, Karatu','Arusha, Arusha Mjini','Arusha, Ngorongoro','Dar es salaam, Kinondoni','Dar es salaam, Temeke','Dar es salaam, Ilala','Dodoma, Dodoma Mjini','Dodoma, Chamwino','Dodoma, Kongwa','Dodoma, Mpwapwa','Iringa, Iringa Mjini','Iringa, Njombe Mjini','Iringa, Kilolo','Iringa, Makete','Kagera, Bukoba','Kagera, Chato','Kagera, Ngara','Kagera, Misenyi','Kagera, Ngara','Kigoma, Kigoma Mjini','Kigoma, Kibondo','Kigoma, Kasulu','Kilimanjaro, Moshi','Kilimanjaro, Rombo','Kilimanjaro, Same','Kilimanjaro, Mwanga','Lindi, Kilwa','Lindi, Lindi Mjini','Lindi, Nachingwea','Lindi, Liwale','Manyara, Babati','Manyara, Simanjiro','Manyara, Hanang','Manyara, Mbulu','Mara, Bunda','Mara, Musoma','Mara, Tarime','Mbeya, Chunya','Mbeya, Ileje','Mbeya, Mbeya Mjini','Mbeya, Mbozi','Morogoro, Morogoro Mjini','Morogoro, Mvomero','Morogoro, Kilombero','Morogoro, Ulanga','Morogoro, Kilombero','Morogoro, Kilosa','Mtwara, Mtwara Mjini','Mtwara, Nanyumbu','Mtwara, Newala','Mtwara, Tandahimba','Mwanza, Sengerema','Mwanza, Ilemela','Mwanza, Geita','Mwanza, Sengerema','Mwanza, Ukerewe','Mwanza, Kwimba','Pwani, Bagamoyo','Pwani, Kibaha','Pwani, Kisarawe','Pwani, Mafia','Pwani, Mkuranga','Pwani, Rufiji','Rukwa, Mpanda','Rukwa, Sumbawanga','Rukwa, Nkasi','Ruvuma, Songea','Ruvuma, Mbinga','Ruvuma, Namtumbo','Ruvuma, Tunduru','Shinyanga, Bariadi','Shinyanga, Shinyanga Mjini','Shinyanga, Kahama','Shinyanga, Meatu','Shinyanga, Maswa','Singida, Iramba','Singida, Manyoni','Singida, Singida Mjini','Tabora, Igunga','Tabora, Nzega','Tabora, Tabora Mjini','Tabora, Urambo','Tabora, Uyui','Tanga, Handeni','Tanga, Kilindi','Tanga, Korogwe','Tanga, Lushoto','Tanga, Mkinga','Tanga, Muheza','Tanga, Pangani','Tanga, Tanga Mjini'
        );
        return $this->courseLocations;
    }

    /**
     * Returns array of dummy sponsor
     * @return array
     */
    public function addDummySponsors()
    {
        // Load Public Data
        $this->sponsor = Array(
            'Development Partner','Employer','Ministry of Health and Social Welfare','Other','Self Sponsored'
        );
        return $this->sponsor;
    }


	public function load(ObjectManager $manager)
	{
        // Populate dummy forms
        $this->addDummyFemaleNames();
        $this->addDummyMaleNmes();
        $this->addDummyCourseNames();
        $this->addDummyCourseLocations();
        $this->addDummySponsors();

        $loadUserData = new LoadUserData();
        $loadUserData->addDummyUsers();
        $dummyUsers = $loadUserData->getUsers();

        $loadFieldData = new LoadFieldData();
        $loadFieldData->addDummyFields();
        $dummyFields = $loadFieldData->getFields();

        $loadFormData = new LoadFormData();
        $loadFormData->addDummyForms();
        $dummyForms = $loadFormData->getForms();

        $organiastionunits = $manager->getRepository('HrisOrganisationunitBundle:Organisationunit')->findAll();

        /*
         * Add data to facilities
         */
        if(!empty($organiastionunits)) {
            foreach($organiastionunits as $organiastionunitKey=>$organisationunit) {
                /*
                 * Assign data to dispensary, hospital and health centres only.
                 */
                if( preg_match('/dispensary|hospital|health centre|council/i',$organisationunit->getLongname()) ) {
                    // Initiate record entering
                    // Enter two records for each orgunit
                    for($recordIncr=0; $recordIncr < $this->recordsPerOrganisationunit;$recordIncr++) {
                        $record = new Record();
                        $record->setOrganisationunit($organisationunit);
                        // Enter record for public and private form
                        $formNames=Array('Public Employee Form','Private Employee Form');
                        $formName=$formNames[array_rand($formNames,1)];
                        if(empty($formName)) $formName='Public Employee Form';
                        $form = $manager->getRepository('HrisFormBundle:Form')->findOneBy(array('name'=>$formName));

                        // Find history fields belonging to this form for population of data
                        $queryBuilder = $manager->createQueryBuilder();
                        $historyFields = $queryBuilder->select('field')
                            ->from('HrisFormBundle:Field','field')
                            ->join('field.formFieldMember','formFieldMember')
                            ->join('formFieldMember.form','form')
                            ->where('form.id=:formId')
                            ->andWhere('field.hashistory=True')
                            ->setParameter('formId',$form->getId())
                            ->getQuery()->getResult();

                        $record->setForm($form);
                        $record->setComplete(True);
                        $record->setCorrect(True);
                        $record->setHashistory(False);
                        $record->setHastraining(False);
                        $dummyUserKey = array_rand($dummyUsers,1);
                        $dummyUsername = $dummyUsers[$dummyUserKey]['username'];
                        $record->setUsername($dummyUsername);
                        // Constructing a Value Array
                        // @todo removing hard-coding of HrisRecordBundle:Record values
                        $value = Array();
                        // Fetch all field members belonging to the form and add records

                        // Roll a dice with gender of employee to pick name
                        $genders = Array('Male','Female');
                        $gender_picked = array_rand($genders,1);

                        $formFieldMembers = $manager->getRepository('HrisFormBundle:FormFieldMember')->findBy(array('form'=>$form));
                        foreach($formFieldMembers as $formFieldMemberKey=>$formFieldMember) {
                            /**
                             * Made dynamic, on which field column is used as key, i.e. uid, name or id.
                             */
                            // Translates to $formFieldMember->getField()->getUid()
                            // or $formFieldMember->getField()->getUid() depending on value of $recordKeyName
                            $recordKeyName = ucfirst(Record::getFieldKey());
                            $valueKey = call_user_func_array(array($formFieldMember->getField(), "get${recordKeyName}"),array());
                            if(
                                $formFieldMember->getField()->getName()=="Firstname" ||
                                $formFieldMember->getField()->getName()=="Middlename" ||
                                $formFieldMember->getField()->getName()=="Surname" ||
                                $formFieldMember->getField()->getName()=="NextofKin"
                            ) {
                                // Deal with names
                                if($gender_picked=="Female" && ( $formFieldMember->getField()->getName()=="Firstname" || $formFieldMember->getField()->getName()=="NextofKin") ) {
                                    $value[$valueKey] = $this->femaleNames[array_rand($this->femaleNames,1)];
                                }else {
                                    $value[$valueKey] = $this->maleNames[array_rand($this->maleNames,1)];
                                }
                                if($formFieldMember->getField()->getName()=="NextofKin" ) {
                                    $value[$valueKey] .= ' '.$this->maleNames[array_rand($this->maleNames,1)];
                                }
                                //@todo remove hard-coding of instance
                                // used later for instance formulation
                                if($formFieldMember->getField()->getName()=="Firstname") $firstName =$value[$valueKey];
                                if($formFieldMember->getField()->getName()=="Middlename") $middleName =$value[$valueKey];
                                if($formFieldMember->getField()->getName()=="Surname") $surname =$value[$valueKey];

                            }else if($formFieldMember->getField()->getInputType()->getName()=="Select") {
                                // Deal with select

                                /**
                                 * Made dynamic, on which field column is used as key, i.e. uid, name or id.
                                 */
                                // Translates to $fieldOptions[0]->getUid()
                                // or $fieldOptions[0]->getValue() depending on value of $recordKeyName
                                // $fieldOptionKey = ucfirst($record->getFieldOptionKey());
                                //$valueKey = call_user_func_array(array($fieldOptions[0], "get${fieldOptionKey}"),array());
                                $fieldOptionKey = ucfirst(Record::getFieldOptionKey());

                                $fieldOptions = $manager->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$formFieldMember->getField()));
                                // For case of gender choose match name with gender
                                if($formFieldMember->getField()->getName()=="Sex") {
                                    // Made FieldOption key to store in record value array dynamic.
                                    if($fieldOptions[0]->getValue()==$gender_picked) $value[$valueKey] = call_user_func_array(array($fieldOptions[0], "get${fieldOptionKey}"),array());
                                    else $value[$valueKey] = call_user_func_array(array($fieldOptions[1], "get${fieldOptionKey}"),array());
                                }else {
                                    // Made fieldOption key to store in record value array dynamic
                                    $value[$valueKey] = call_user_func_array(array($fieldOptions[array_rand($fieldOptions,1)], "get${fieldOptionKey}"),array());
                                }
                            }else if($formFieldMember->getField()->getInputType()->getName()=="Date") {
                                // Deal with dates
                                // If birth date pick 20 - 55 date range
                                // If employment data set it to birth date range+18
                                // If confirmation date, set it to employment date+1
                                // If promotion date, set it to confirmation+3
                                $beginDateStart=50;
                                $beginDateStop=75;
                                $endDateStart=40;
                                $endDateStop=50;
                                if($formFieldMember->getField()->getName()=="DateOfBirth") {
                                    $beginDateStart=50;
                                    $beginDateStop=75;
                                    $endDateStart=40;
                                    $endDateStop=50;
                                }elseif($formFieldMember->getField()->getName()=="DateofFirstAppointment") {
                                    $beginDateStart-=18;
                                    $beginDateStop-=18;
                                    $endDateStart-=18;
                                    $endDateStop-=18;
                                }elseif($formFieldMember->getField()->getName()=="DateofConfirmation") {
                                    $beginDateStart-=19;
                                    $beginDateStop-=19;
                                    $endDateStart-=19;
                                    $endDateStop-=19;
                                }elseif($formFieldMember->getField()->getName()=="DateofLastPromotion") {
                                    $beginDateStart-=22;
                                    $beginDateStop-=22;
                                    $endDateStart-=20;//avoid negative 20-22 number(messes-up logic)
                                    $endDateStop-=22;
                                }
                                $value[$valueKey] = new \DateTime($this->getRandDate(array($beginDateStart,$beginDateStop),array($endDateStart,$endDateStop)));
                                //@todo remove hard-coding of instance
                                if($formFieldMember->getField()->getName()=="DateOfBirth") $dateOfBirth =$value[$valueKey];
                            }else if($formFieldMember->getField()->getInputType()->getName()=="Text") {
                                // Deal with numbers
                                if($formFieldMember->getField()->getName()=="NumberofChildrenDependants" ) {
                                    $value[$valueKey] = rand(0,10);
                                }elseif($formFieldMember->getField()->getName()=="CheckNumber" ) {
                                    $value[$valueKey] = rand(9999999,9999999999);
                                }elseif($formFieldMember->getField()->getName()=="EmployersFileNumber") {
                                    $value[$valueKey] = "FN/".rand(100,100000);
                                }elseif($formFieldMember->getField()->getName()=="RegistrationNumber") {
                                    $value[$valueKey] = "RB/".rand(10,10000);
                                }elseif($formFieldMember->getField()->getName()=="MonthlyBasicSalary") {
                                    $value[$valueKey] = rand(100,1500).'000';
                                }else {
                                    $value[$valueKey] = $this->maleNames[array_rand($this->maleNames,1)]." Street";
                                }
                            }else if($formFieldMember->getField()->getInputType()->getName()=="TextArea") {
                                // Deal with domicile, contact
                                if(
                                    $formFieldMember->getField()->getName()=="ContactsofEmployee" ||
                                    $formFieldMember->getField()->getName()=="ContactsofNextofKin"
                                ) {
                                    $value[$valueKey] = "+255".rand(6,7).rand(53,69).rand(001,998).rand(001,998);
                                }
                            }
                        }
                        $instance=md5($firstName.$middleName.$surname.$dateOfBirth->format('Y-m-d'));
                        $record->setInstance($instance);
                        $record->setValue($value);
                        //@todo check for uniqueness of instance and unique fields
                        $recordReference = strtolower(str_replace(' ','',$record->getInstance())).'-record';
                        $this->addReference($recordReference, $record);
                        $manager->persist($record);

                        // Randomly on flip of a coin assign history & training data
                        $outcomes=Array(True,False);
                        if($outcomes[array_rand($outcomes,1)]) {

                            // Assign randomly between 2 to 4 histories per record
                            $numberofHistoriesToAssign=Array(1,2);
                            for($incr=0; $incr< $numberofHistoriesToAssign[array_rand($numberofHistoriesToAssign,1)]; $incr++ ) {
                                $history = new History();
                                $history->setRecord($record);
                                $history->setUsername($record->getUsername());
                                //Calculate start date ranging starting form now-2yrs back and and stopping between 3-5 years back
                                $beginDateStart=3;$beginDateStop=5;
                                $endDateStart=0;$endDateStop=2;
                                $startDate = new \DateTime($this->getRandDate(array($beginDateStart,$beginDateStop),array($endDateStart,$endDateStop)));
                                $history->setStartdate($startDate);
                                $historyField = $historyFields[array_rand($historyFields,1)];
                                //echo get_class($historyField);exit;
                                // If history field is Combo assign combo if text assign text
                                if($historyField->getInputType()=="Select") {
                                    $historyFieldOptions = $historyField->getFieldOption();
                                    $historyFieldOptions = $historyFieldOptions->getValues();
                                    $selectedHistoryOption = $historyFieldOptions[array_rand($historyFieldOptions,1)];
                                    $historyValue = $selectedHistoryOption->getValue();
                                }elseif($historyField->getInputType()=="Date") {
                                    //Calculate start date ranging starting form 1-3yrs back and and stopping between 5-8 years back
                                    $beginDateStart=5;$beginDateStop=8;
                                    $endDateStart=1;$endDateStop=3;
                                    $historyDateObject = new \DateTime($this->getRandDate(array($beginDateStart,$beginDateStop),array($endDateStart,$endDateStop)));
                                    $historyValue = $historyDateObject->format('Y-m-d');
                                }else {
                                    // Deal with string history fields
                                    if(
                                        $historyField->getName()=="Firstname" ||
                                        $historyField->getName()=="Middlename" ||
                                        $historyField->getName()=="Surname" ||
                                        $historyField->getName()=="NextofKin"
                                    ) {
                                        // Deal with names
                                        if($gender_picked=="Female" && ( $historyField->getName()=="Firstname" || $historyField->getName()=="NextofKin") ) {
                                            $historyValue = $this->femaleNames[array_rand($this->femaleNames,1)];
                                        }else {
                                            $historyValue = $this->maleNames[array_rand($this->maleNames,1)];
                                        }
                                        if($historyField->getName()=="NextofKin" ) {
                                            $historyValue .= ' '.$this->maleNames[array_rand($this->maleNames,1)];
                                        }
                                    }else if($historyField->getInputType()->getName()=="Text") {
                                        // Deal with numbers
                                        if($historyField->getName()=="NumberofChildrenDependants" ) {
                                            $historyValue = rand(0,10);
                                        }elseif($historyField->getName()=="CheckNumber" ) {
                                            $historyValue = rand(9999999,9999999999);
                                        }elseif($historyField->getName()=="EmployersFileNumber") {
                                            $historyValue = "FN/".rand(100,100000);
                                        }elseif($historyField->getName()=="RegistrationNumber") {
                                            $historyValue = "RB/".rand(10,10000);
                                        }elseif($historyField->getName()=="MonthlyBasicSalary") {
                                            $historyValue = rand(100,1500).'000';
                                        }else {
                                            $historyValue = $this->maleNames[array_rand($this->maleNames,1)]." Street";
                                        }
                                    }else if($historyField->getInputType()->getName()=="TextArea") {
                                        // Deal with domicile, contact
                                        if(
                                            $historyField->getName()=="ContactsofEmployee" ||
                                            $historyField->getName()=="ContactsofNextofKin"
                                        ) {
                                            $historyValue = "+255".rand(6,7).rand(53,69).rand(001,998).rand(001,998);
                                        }
                                    }
                                }
                                $reason = $historyField->getCaption() . " changed.";
                                $history->setField($historyField);
                                $history->setHistory($historyValue);
                                $history->setReason($reason);
                                $manager->persist($history);
                                unset($history);
                            }
                            $record->setHashistory(True);
                            $manager->persist($record);
                        }
                        if($outcomes[array_rand($outcomes,1)]) {
                            // Assign randomly between 2 to 4 trainings per record
                            $numberofTrainingsToAssign=Array(1,2);
                            for($incr=0; $incr< $numberofTrainingsToAssign[array_rand($numberofTrainingsToAssign,1)]; $incr++ ) {
                                $training = new Training();
                                $training->setRecord($record);
                                $training->setCoursename($this->courseNames[array_rand($this->courseNames,1)]);
                                $training->setCourselocation($this->courseLocations[array_rand($this->courseLocations,1)]);
                                $training->setSponsor($this->sponsor[array_rand($this->sponsor,1)]);

                                //Calculate start date ranging starting form 9-10yrs back and and stopping between 10-12 years back
                                $beginDateStart=10;$beginDateStop=12;
                                $endDateStart=9;$endDateStop=10;
                                $startDate = new \DateTime($this->getRandDate(array($beginDateStart,$beginDateStop),array($endDateStart,$endDateStop)));
                                //Calculate end date ranging starting form 11-13yrs back and and stopping between 13-15 years back
                                $beginDateStart=13;$beginDateStop=15;
                                $endDateStart=11;$endDateStop=13;
                                $endDate = new \DateTime($this->getRandDate(array($beginDateStart,$beginDateStop),array($endDateStart,$endDateStop)));

                                $training->setStartdate($startDate);
                                $training->setEnddate($endDate);
                                $training->setUsername($record->getUsername());
                                $manager->persist($training);
                                unset($training);
                            }
                            $record->setHastraining(True);
                            $manager->persist($record);
                        }
                        unset($record);

                    }
                }
            }
        }
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        //LoadIndicator preceeds
		return 11;
        //LoadResourceTable follows
	}

}
