<?php
global $city;
global $branch;
$links = array();
//Get path of URL
$path = explode(' ', $_SERVER['REQUEST_URI']);
$path = $path[0];
//$slug = "bedford";
//Get name of city from URL - word after last "/" -- old way
//preg_match("/[^\/]+$/", $path, $matches);
//$city = $matches[0];

/**
 * Finds a substring between two strings
 * @param  string $string The string to be searched
 * @param  string $start The start of the desired substring
 * @param  string $end The end of the desired substring
 * @param  bool   $greedy Use last instance of`$end` (default: false)
 * @return string
 */
function find_between(string $string, string $start, string $end, bool $greedy = false) {
    $start = preg_quote($start);
    $end   = preg_quote($end);
 
    $format = '/(%s)(.*';
    if (!$greedy) $format .= '?';
    $format .= ')(%s)/';
 
    $pattern = sprintf($format, $start, $end);
    preg_match($pattern, $string, $matches);
 
    return $matches[2];
}

//$test_path = "https://wordpress-001:8890/local/texas/bedford-home-security";
$string = $path;
$start  = 'oklahoma';
$end    = '-home-security';
$greedy = false;
//var_dump(find_between($string, $start, $end));

$city = find_between($string, $start, $end);
$city = preg_match("/[^\/]+$/", $city, $matches);
$city = $matches[0];

//search arrays
function array_find_deep($array, $search, $keys = array()) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $sub = array_find_deep($value, $search, array_merge($keys, array(
                $key
            )));
            if (count($sub)) {
                return $sub;
            }
        } elseif ($value === $search) {
            return array_merge($keys, array(
                $key
            ));
        }
    }
    return array();
}

//if city name matches one of these, make the page slug the name. If not redirect to homepage
$cities = array(
    'tulsa' => array(
        "adair", "afton", "alderson", "anderson", "antlers", "arkoma", "atoka", "avant", "barnsdall", "bartlesville", "baxter-springs", "beggs", "bella-vista", "bentonville", "bernice", "big-cabin", "bixby", "blocker", "bluejacket", "bokoshe", "boley", "boswell", "boynton", "braggs", "bristow", "broken-arrow", "broken-bow", "burbank", "cameron", "canadian", "caney", "carlton-landing", "carthage", "castle", "catoosa", "centerton", "checotah", "chelsea", "cherryvale", "chetopa", "chouteau", "claremore", "clayton", "clearview", "cleveland", "coffeyville", "colcord", "collinsville", "columbus", "commerce", "cookson", "copan", "council-hill", "coweta", "crowder", "cushing", "daisy", "davenport", "decatur", "depew", "dewey", "diamond", "disney", "drumright", "dustin", "eucha", "eufaula", "fairfax", "fairland", "fairview", "fort-gibson", "fort-towson", "galena", "glenpool", "gore", "granby", "gravette", "grove", "haileyville", "hanna", "hartshorne", "haskell", "haywood", "heavener", "henryetta", "hominy", "honobia", "howe", "hugo", "hulbert", "idabel", "indianola", "inola", "jane", "jay", "jenks", "jennings", "joplin", "kansas", "kellyville", "keota", "ketchum", "kiefer", "kiowa", "krebs", "lake-eufaula", "langley", "locust-grove", "loma-linda", "maimi", "mannford", "maramec", "mcalester", "mccurtain", "miami", "milfay", "monett", "monkey-island", "morris", "mounds", "muldrow", "muskogee", "neosho", "north-miami", "nowata", "ochelata", "oilton", "okay", "okemah", "okmulgee", "oktaha", "oologah", "osage", "oswego", "owasso", "paden", "park-hill", "parsons", "pawhuska", "pawnee", "pea-ridge", "picher", "pineville", "pittsburg", "pocola", "porter", "porum", "poteau", "preston", "proctor", "prue", "pryor", "purdy", "quapaw", "quinton", "ralston", "ramona", "rattan", "rentiesville", "riverton", "roland", "rose", "salina", "sallisaw", "sand-springs", "sapulpa", "sawyer", "seligman", "seneca", "shady-point", "shidler", "skiatook", "south-coffeyville", "sparks", "spavinaw", "sperry", "spiro", "stigler", "stilwell", "stotts-city", "stroud", "stuart", "sulphur-springs", "swink", "taft", "tahlequah", "talala", "talihina", "terlton", "thayer", "tryon", "tuskahoma", "twin-oaks", "valliant", "verona", "vian", "vinita", "wagoner", "wann", "warner", "washburn", "watts", "webb-city", "webbers-falls", "welch", "weleetka", "welling", "welty", "westville", "wheaton", "whitefield", "whitesboro", "wilburton", "wister", "wright-city", "wyandotte", "wynona","yale","blue","snow","simms"
    ),
    'oklahoma-city' => array(
        "ada", "addington", "agra", "alex", "allen", "altus", "alva", "amber", "ames", "amorita", "anadarko", "apache", "arapaho", "arcadia", "ardmore", "asher", "atwood", "beaver", "bennington", "bessie", "bethany", "bethel-acres", "billings", "binger", "blackwell", "blair", "blanchard", "bokchito", "bowlegs", "bradley", "bray", "buffalo", "burns-flat", "butler", "byars", "byng", "byron", "cache", "caddo", "calera", "calumet", "canute", "carnegie", "carney", "carter", "cartwright", "cashion", "cement", "chandler", "chattanooga", "cherokee", "cheyenne", "chickasha", "choctaw", "clinton", "coalgate", "colbert", "coleman", "comanche", "cordell", "corn", "covington", "coyle", "crawford", "crescent", "custer", "custer-city", "cyril", "dale", "davis", "del-city", "devol", "dibble", "dill-city", "dougherty", "douglas", "dover", "duke", "duncan", "durant", "durham", "eakly", "earlsboro", "edmond", "el-reno", "eldorado", "elgin", "elk-city", "elmer", "elmore-city", "empire-city", "enid", "erick", "etowah", "fairview", "faxon", "fletcher", "forest-park", "fort-cobb", "foss", "foster", "fox", "frederick", "gage", "garber", "geary", "geronimo", "glencoe", "goldsby", "gotebo", "gould", "gracemont", "graham", "grandfield", "granite", "guthrie", "guymon", "hammon", "harrah", "headrick", "healdton", "hendrix", "hennessey", "hillsdale", "hinton", "hobart", "holdenville", "hollis", "hopeton", "hydro", "indiahoma", "isabella", "jones", "kaw-city", "kenefic", "kingfisher", "kingston", "konawa", "kremlin", "lahoma", "lamar", "langston", "lawton", "lebanon", "leedey", "lexington", "lindsay", "loco", "lone-grove", "lone-wolf", "longdale", "lookeba", "luther", "macomb", "madill", "manchester", "mangum", "marietta", "marlow", "marshall", "maud", "maysville", "mcloud", "mead", "medford", "medicine-park", "meeker", "meers", "meridian", "midwest-city", "milburn", "mill-creek", "minco", "moore", "mooreland", "morrison", "mountain-park", "mountain-view", "mulhall", "mustang", "mutual", "newalla", "newcastle", "newkirk", "nichols-hills", "nicoma-park", "ninnekah", "noble", "norman", "oakland", "okarche", "okeene", "olustee", "omega", "orlando", "overbrook", "paoli", "pauls-valley", "perkins", "perry", "piedmont", "pink", "pocasset", "ponca-city", "pond-creek", "prague", "purcell", "putnam", "reydon", "ringling", "ringwood", "rocky", "roff", "rush-springs", "saint-louis", "sasakwa", "sayre", "seiling", "seminole", "sentinel", "shattuck", "shawnee", "snyder", "spencer", "springer", "sterling", "stillwater", "stonewall", "stratford", "strong-city", "sulphur", "sweetwater", "taloga", "tatums", "tecumseh", "temple", "terral", "thackerville", "the-village", "thomas", "tishomingo", "tonkawa", "tribbey", "tupelo", "turpin", "tuttle", "tyrone", "union-city", "valley-brook", "velma", "verden", "vici", "village", "walters", "wanette", "wapanucka", "warr-acres", "washington", "watonga", "waukomis", "waurika", "wayne", "waynoka", "weatherford", "wellston", "wetumka", "wewoka", "wheatland", "wilson", "woodward", "wynnewood", "yukon"
    )
);

if (array_find_deep($cities, $city)) {
    $slug = $city;  
} else {
    // Redirect browser 
    header("Location: /");
    exit;
}

// localization

if (in_array($city, $cities['tulsa'])) {
    //$test   = "This is the Tulsa area, so I'll add the Tulsa area office info.";
    $links  = $cities['tulsa'];
    asort($links);
    $branch = 'Tulsa';
    $state = 'Oklahoma';
} else if (in_array($city, $cities['oklahoma-city'])) {
    $links  = $cities['oklahoma-city'];
    asort($links);
    $branch = 'Oklahoma City';
    $state = 'Oklahoma';
} else {
    //$test = "none";
    null;
}

$otherAreas = implode(", ", array_map(function($item) {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $item_ref = ucwords(str_replace("-", " ", $item));
    //$item = $item . '-home-security';
    $item = $root . 'oklahoma/' . $item . '-home-security';
    return "<a href='$item'>$item_ref</a>";
}, $links));

//Capitalize first word in city name and take out hyphens
$city = ucwords(str_replace("-", " ", $city));
$copy_city = '<strong>' . $city . '</strong>';

//Change Metro Area
function find_metro($branch) {
    if ($branch != 'Dallas' && $branch != 'Fort Worth') {
        $branch_metro = $branch . ' Metro Area';
    } else {
        $branch_metro = 'DFW Metroplex';
    };
return $branch_metro;
}

//Change Price
function find_price($branch) {
    if ($branch != 'Sacramento' && $branch != 'San Diego' && $branch != 'Fresno') {
        $price = '$15.95';
    } else {
        $price = '$19.95';
    };
return $price;
}

// require_once('includes/local-copy.php');

// switch ($branch) {
//     case "Tulsa":
//         $topCopy = $localCopy001;
//         break;
//     case "Dallas":
//         $topCopy = $localCopy003;
//         break;
//     case "Fort Worth":
//         $topCopy = $localCopy003;
//         break;
//     case "Springfield":
//         $topCopy = $localCopy004;
//         break;
//     case "Sarasota":
//         $topCopy = $localCopy005;
//         break;
//     case "San Diego":
//         $topCopy = $localCopy001;
//         break;
//     case "San Antonio":
//         $topCopy = $localCopy002;
//         break;
//     case "Sacramento":
//         $topCopy = $localCopy003;
//         break;
//     case "Phoenix":
//         $topCopy = $localCopy004;
//         break;
//     case "Oklahoma City":
//         $topCopy = $localCopy005;
//         break;
//     case "Kansas City":
//         $topCopy = $localCopy001;
//         break;
//     case "Houston":
//         $topCopy = $localCopy002;
//         break;
//     case "Stafford":
//         $topCopy = $localCopy002;
//         break;
//     case "Austin":
//         $topCopy = $localCopy003;
//         break;
//     case "Wichita":
//         $topCopy = $localCopy004;
//         break;
//     case "Fresno":
//         $topCopy = $localCopy002;
//         break;
//     default:
//         $topCopy = $localCopy005;
// };

?>

<?php
// Wordpress custom functions
//add_filter('wpseo_title', 'filter_product_wpseo_title');
// page title with city 
function filter_product_wpseo_title($title) {
    global $city;
    $title = $city . " - Best Home Security | Alarm Systems";
    return $title;
}
?>
<?php
function custom_add_meta_description_tag() {
    global $city;
?>
<meta name="description" content="Best Home Security Systems in <?php echo $city; ?> at great prices! We offers several packages that are up to 25% off RETAIL! Switch and save Alarm Monitoring starting at $15.95!" /> 
<?php }

//add_action('wp_head', 'custom_add_meta_description_tag', 1);
//add_filter('body_class', 'my_body_classes');

// function my_body_classes($classes) {
//     $classes[] = 'suburbs';
//     return $classes;
// }
?>