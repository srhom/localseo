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
$start  = 'texas';
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
    'dallas' => array(
        "addison", "allen", "anna", "balch-springs", "blue-ridge", "buckingham", "caddo-mills", "campbell", "canton", "carrollton", "cedar-hill", "celina", "combine", "commerce", "coppell", "corsicana", "crandall", "denison", "denton-county", "desoto", "dfw-airport", "duncanville", "enchanted-oaks", "ennis", "fairview", "farmers-branch", "farmersville", "fate", "ferris", "forney", "frisco", "garland", "glenn-heights", "grand-prairie", "greenville", "gun-barrel-city", "gun-barrel-cy", "gunter", "heartland", "heath", "hickory-creek", "highland-park", "hutchins", "irving", "josephine", "kaufman", "kemp", "lake-dallas", "lakewood-village", "lancaster", "lavon", "lewisville", "little-elm", "lone-oak", "lucas", "mabank", "mc-kinney", "mckinney", "melissa", "mesquite", "murphy", "nevada", "oak-leaf", "oak-point", "ovilla", "palmer", "parker", "plano", "pottsboro", "princeton", "prosper", "quinlan", "red-oak", "rice", "richardson", "rockwall", "rowlett", "royse-city", "sachse", "scurry", "seagoville", "seven-points", "sherman", "st-paul", "sunnyvale", "terrell", "the-colony", "tool", "university-park", "van-alstyne", "waxahachie", "west-tawakoni", "weston", "whitewright", "whitney", "wilmer", "wolfe-city", "wylie"
    ),
    'ft-worth' => array(
        "acton", "aledo", "alvarado", "argyle", "arlington", "aubrey", "aurora", "azle", "bartonville", "bedford", "benbrook", "blue-mound", "boyd", "brock", "burleson", "cleburne", "colleyville", "copper-canyon", "corinth", "cresson", "crossroads", "crowley", "dalworthington-gardens", "decatur", "denton", "denton-county", "dish", "double-oak", "edgecliff-village", "euless", "everman", "flower-mound", "flowermound", "forest-hill", "gainesville", "glen-rose", "godley", "granbury", "grandview", "grapevine", "haltom-city", "haslet", "highland-village", "hudson-oaks", "hurst", "italy", "joshua", "justin", "keller", "kennedale", "krugerville", "krum", "lake-kiowa", "lake-worth", "lakeside", "lantana", "lewisville", "mansfield", "maypearl", "midlothian", "millsap", "mineral-wells", "newark", "north-richland-hills", "northlake", "pantego", "paradise", "pilot-point", "ponder", "providence-village", "rhome", "richland-hills", "richland-hls", "rio-vista", "river-oaks", "roanoke", "saginaw", "sanger", "sansom-park", "savannah", "shady-shores", "south-lake", "southlake", "springtown", "trophy-club", "valley-view", "venus", "watauga", "weatherford", "westlake", "westworth-village", "white-settlement", "wichita-falls", "willow-park"
    ),
    'san-antonio' => array(
        "adkins", "alamo-heights", "aransas-pass", "atascosa", "balcones-heights", "bandera", "bastrop", "beeville", "bergheim", "bigfoot", "blanco", "boerne", "bulverde", "campbellton", "canyon-lake", "castle-hills", "castroville", "center-point", "china-grove", "cibolo", "comfort", "concan", "converse", "corpus-christi", "cost", "cotulla", "devine", "dilley", "elmendorf", "fair-oaks", "fair-oaks-ranch", "falls-city", "fayetteville", "floresville", "fredericksburg", "garden-ridge", "gonzales", "hallettsville", "harper", "helotes", "hobson", "hollywood-park", "hondo", "johnson-city", "jourdanton", "karnes-city", "kendalia", "kenedy", "kerrville", "kingsbury", "kirby", "la-coste", "la-grange", "la-vernia", "lakehills", "lavernia", "leming", "leon-valley", "live-oak", "luling", "lytle", "marion", "mathis", "mcqueeney", "mico", "mountain-home", "natalia", "new-braunfels", "odem", "olmos-park", "pearsall", "pipe-creek", "pleasanton", "port-aransas", "portland", "poteet", "rio-medina", "robstown", "round-top", "saint-hedwig", "santa-clara", "schertz", "seguin", "selma", "shavano-park", "somerset", "spring-branch", "stockdale", "stonewall", "sutherland-springs", "terrell-hills", "universal-city", "universal-cty", "utopia", "uvalde", "von-ormy", "windcrest", "yoakum"
    ),
    'houston' => array(
        "addicks-barker", "aldine", "ames", "anderson", "atascocita", "baytown", "beach-city", "beaumont", "bedias", "bellview", "bellville", "brenham", "bryan", "burton", "cat-spring", "channelview", "chappell-hill", "cleveland", "coldspring", "college-station", "conroe", "cove", "crabbs-prairie", "crosby", "cypress", "dayton", "goodrich", "hankamer", "hardin", "hempstead", "highlands", "hilshire-village", "hockley", "huffman", "humble", "huntsville", "iola", "jersey-village", "katy", "kingwood", "klein", "kurten", "liberty", "livingston", "lumberton", "magnolia", "mont-belvieu", "montgomery", "navasota", "new-caney", "new-waverly", "oak-ridge-north", "onalaska", "orange", "park-row", "pattison", "pinehurst", "plantersville", "port-arthur", "port-neches", "porter", "rayford", "richards", "riverside", "roman-forest", "rye", "sealy", "shenandoah", "shepherd", "splendora", "spring", "stagecoach", "the-woodlands", "tomball", "trinity", "waller", "wallisville", "washington", "willis", "winnie"
    ),
    'stafford' => array(
        "addicks", "alvin", "anahuac", "angleton", "arcola", "bacliff", "bayou-vista", "beasley", "bellaire", "brazoria", "brookshire", "clear-lake-shores", "clute", "crystal-beach", "damon", "danbury", "deer-park", "dickinson", "east-bernard", "el-lago", "freeport", "fresno", "friendswood", "fulshear", "galena-park", "galveston", "galveston-county", "gilchrist", "hitchcock", "iowa-colony", "jacinto-city", "jamaica-beach", "katy", "kemah", "la-marque", "la-porte", "lake-jackson", "league-city", "liverpool", "manvel", "meadows-place", "missouri-city", "nassau-bay", "needville", "orchard", "oyster-creek", "pasadena", "pattison", "pearland", "port-bolivar", "richmond", "richwood", "rosenberg", "rosharon", "san-leon", "santa-fe", "seabrook", "shoreacres", "south-houston", "sugar-land", "surfside-beach", "sweeny", "taylor-lake-village", "texas-city", "tiki-island", "wallis", "webster", "west-university-place", "wharton"
    ),
    'austin' => array(
        "andice", "bartlett", "bastrop", "bee-cave", "bee-caves", "belton", "bertram", "briggs", "buchanan-dam", "buda", "burnet", "cameron", "cedar-creek", "cedar-park", "china-spring", "copperas-cove", "cottonwood-shores", "dale", "del-valle", "driftwood", "dripping-springs", "elgin", "elm-mott", "evant", "fischer", "florence", "fort-hood", "gatesville", "georgetown", "giddings", "granger", "granite-shls", "granite-shoals", "harker-heights", "hearne", "heidenheimer", "horseshoe-bay", "hutto", "jarrell", "jonestown", "kempner", "killeen", "kingsland", "kyle", "lago-vista", "lakeway", "lampasas", "leander", "ledbetter", "lexington", "liberty-hill", "llano", "lockhart", "manchaca", "manor", "marble-falls", "marlin", "martindale", "mcdade", "meadowlakes", "milano", "morgans-point", "mountain-city", "mustang-ridge", "nolanville", "old-round-rock", "paige", "pflugerville", "point-venture", "red-rock", "rockdale", "rogers", "round-mountain", "round-rock", "salado", "san-marcos", "smithville", "spicewood", "sunrise-beach", "sunset-valley", "taylor", "temple", "the-hills", "thorndale", "thrall", "volente", "waco", "west-lake-hills", "wimberley"
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

if (in_array($city, $cities['dallas'])) {
    $links  = $cities['dallas'];
    asort($links);
    $branch = 'Dallas';
    $state = 'Texas';
} else if (in_array($city, $cities['ft-worth'])) {
    $links  = $cities['ft-worth'];
    asort($links);
    $branch = 'Fort Worth';
    $state = 'Texas';
} else if (in_array($city, $cities['ft-worth'])) {
    $links  = $cities['ft-worth'];
    asort($links);
    $branch = 'Fort Worth';
    $state = 'Texas';
} else if (in_array($city, $cities['san-antonio'])) {
    $links  = $cities['san-antonio'];
    asort($links);
    $branch = 'San Antonio';
    $state = 'Texas';
} else if (in_array($city, $cities['houston'])) {
    $links  = $cities['houston'];
    asort($links);
    $branch = 'Houston';
    $state = 'Texas';
} else if (in_array($city, $cities['stafford'])) {
    $links  = $cities['stafford'];
    asort($links);
    $branch = 'Stafford';
    $state = 'Texas';
} else if (in_array($city, $cities['austin'])) {
    $links  = $cities['austin'];
    asort($links);
    $branch = 'Austin';
    $state = 'Texas';
} else {
    //$test = "none";
    null;
}

$otherAreas = implode(", ", array_map(function($item) {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $item_ref = ucwords(str_replace("-", " ", $item));
    //$item = $item . '-home-security';
    $item = $root . 'texas/' . $item . '-home-security';
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