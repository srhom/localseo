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
$start  = 'kansas';
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
    'kansas-city' => array(
        "adrian", "agency", "allendale", "archie", "atchison", "auburn", "baldwin-city", "basehor", "bates-city", "belton", "belvue", "berryton", "bethany", "blue-springs", "bonner-springs", "bonner-sprngs", "boonville", "buckner", "bucyrus", "butler", "cameron", "carbondale", "centerview", "chilhowee", "clay-center", "claycomo", "cleveland", "columbia", "concordia", "country-club", "de-soto", "dearborn", "easton", "edgerton", "edwardsville", "effingham", "eudora", "excelsior-spg", "excelsior-springs", "fairway", "faucett", "fillmore", "fort-scott", "freeman", "garden-city", "gardner", "gladstone", "gower", "grain-valley", "grandview", "grantville", "greenwood", "harrisonville", "hiawatha", "higginsville", "holden", "holt", "holton", "hoyt", "independence", "iola", "junction-city", "kansas-city-north", "kearney", "kingsville", "knob-noster", "la-monte", "lacygne", "lake-lotawana", "lake-winnebago", "lansing", "lathrop", "lawrence", "lawson", "leavenworth", "leawood", "lecompton", "lees-summit", "leeton", "lenexa", "lexington", "liberty", "linn-valley", "lone-jack", "louisburg", "macon", "manhattan", "mayetta", "mayview", "mc-louth", "meriden", "merriam", "milford", "mission", "mission-hills", "mission-woods", "mound-city", "n-kansas-city", "new-century", "no-kansas-city", "north-kansas-city", "northmoor", "oak-grove", "oakview", "odessa", "olathe", "osage-city", "osawatomie", "oskaloosa", "ottawa", "overland-park", "ozawkie", "paola", "parkville", "peculiar", "perry", "platte-city", "plattsburg", "pleasant-hill", "pleasant-valley", "prairie-village", "prairie-vlg", "princeton", "rantoul", "raymore", "raytown", "richmond", "riley", "riverside", "roeland-park", "rossville", "rushville", "saint-george", "saint-joseph", "savannah", "scranton", "sedalia", "shawnee", "shawnee-mission", "silver-lake", "smithville", "spring-hill", "st-joseph", "stanley", "stilwell", "sugar-creek", "tecumseh", "tonganoxie", "topeka", "tracy", "trimble", "valley-falls", "vassar", "village-of-loch-lloyd", "wakarusa", "wamego", "warrensburg", "warsaw", "wathena", "weatherby-lake", "wellsville", "weston", "westwood", "pittsburg"
    ),
    'wichita' => array(
        "abbyville", "andale", "andover", "anthony", "argonia", "arkansas-city", "arlington", "attica", "augusta", "bel-aire", "belle-plaine", "bentley", "benton", "buhler", "burrton", "cambridge", "canton", "cassoday", "cheney", "clearwater", "colwich", "conway-springs", "cunningham", "derby", "douglas", "douglass", "eastborough", "el-dorado", "elk-falls", "emporia", "eureka", "fall-river", "florence", "freeport", "garden-plain", "goddard", "great-bend", "halstead", "hamilton", "harper", "haven", "haysville", "herington", "hesston", "hillsboro", "howard", "hutchinson", "inman", "kechi", "kingman", "latham", "lehigh", "leon", "madison", "maize", "marion", "mcpherson", "medicine-lodge", "milan", "milton", "moundridge", "mount-hope", "mulvane", "newton", "nickerson", "north-newton", "north-wichita", "norwich", "oaklawn", "oxford", "park-city", "peabody", "peck", "plevna", "potwin", "pretty-prairie", "rose-hill", "rosehill", "s-hutchinson", "saint-john", "sedgwick", "south-haven", "south-hutchinson", "sterling", "sylvan-grove", "sylvia", "towanda", "turon", "udall", "valley-center", "viola", "wellingtion", "wellington", "whitewater", "windom", "winfield", "yoder", "silvis", "coffeyville","vinland"
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

if (in_array($city, $cities['kansas-city'])) {
    $links  = $cities['kansas-city'];
    asort($links);
    $branch = 'Kansas City';
    $state = 'Missouri';
} else if (in_array($city, $cities['wichita'])) {
    $links  = $cities['wichita'];
    asort($links);
    $branch = 'Wichita';
    $state = 'Kansas';
} else {
    //$test = "none";
    null;
}

$otherAreas = implode(", ", array_map(function($item) {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $item_ref = ucwords(str_replace("-", " ", $item));
    //$item = $item . '-home-security';
    $item = $root . 'kansas/' . $item . '-home-security';
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