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
$start  = 'california';
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
    'san-diego' => array(
        "aguanga", "alhambra", "aliso-viejo", "alpine", "anaheim", "anaheim-hills", "arcadia", "artesia", "azusa", "balboa-island", "baldwin-park", "bell", "bell-gardens", "bellflower", "bonita", "bonsall", "brea", "buena-park", "campo", "cardiff", "cardiff-by-the-sea", "carlsbad", "carson", "cerritos", "charter-oak", "chino", "chino-hills", "chula-vista", "city-of-industry", "claremont", "chula-vista", "commerce", "compton", "corona", "corona-del-mar", "coronado", "costa-mesa", "coto-de-caza", "covina", "cty-of-cmmrce", "cudahy", "cypress", "del-mar", "descanso", "diamond-bar", "downey", "duarte", "east-los-angeles", "east-rancho-dominguez", "eastvale", "el-cajon", "el-monte", "encinitas", "escondido", "fallbrook", "fontana", "foothill-ranch", "fountain-valley", "fullerton", "garden-grove", "gardena", "glendora", "glendale", "hacienda-heights", "harbor-city", "hawaiian-gardens", "hawthorne", "huntington-beach", "huntington-park", "imperial-beach", "inglewood", "irvine", "jamul", "julian", "jurupa-valley", "la-habra", "la-habra-heights", "la-jolla", "la-mesa", "la-mirada", "la-palma", "la-puente", "la-verne", "ladera-ranch", "laguna-beach", "laguna-hills", "laguna-niguel", "laguna-woods", "lake-forest", "lakeside", "lakewood", "lawndale", "lemon-grove", "lomita", "long-beach", "los-alamitos", "los-angeles", "lynwood", "maywood", "menifee", "mira-loma", "mission-viejo", "monrovia", "montclair", "montebello", "monterey-park", "murrieta", "national-city", "newport-beach", "newport-coast", "norco", "north-tustin", "norwalk", "oceanside", "ontario", "orange", "palos-verdes-estates", "paramount", "phillips-ranch", "pico-rivera", "placentia", "pomona", "poway", "ramona", "rancho-cucamonga", "rancho-mission-viejo", "rancho-palos-verdes", "rancho-santa-fe", "rancho-santa-margarita", "redondo-beach", "riverside", "rolling-hills-estates", "rosemead", "rowland-heights", "san-dimas", "san-gabriel", "san-juan-capistrano", "san-marcos", "san-pedro", "san-ysidro", "santa-ana", "santa-fe-springs", "santa-ysabel", "santee", "seal-beach", "signal-hill", "solana-beach", "south-el-monte", "south-gate", "south-pasadena", "spring-valley", "stanton", "temecula", "temple-city", "torrance", "tustin", "upland", "valley-center", "vernon", "villa-park", "vista", "walnut", "walnut-park", "warner-springs", "west-covina", "westminster", "whittier", "wildomar", "wilmington", "winchester", "windsor-hills", "yorba-linda", "moreno-valley", "san-bernardino"
    ),
    'sacramento' => array(
        "acampo", "alameda", "alamo", "albany", "alta", "american-canyon", "antelope", "antioch", "applegate", "atwater", "auburn", "bay-point", "belmont", "benicia", "berkeley", "bethel-island", "brentwood", "browns-valley", "burlingame", "byron", "cameron-park", "campbell", "carmichael", "castro-valley", "ceres", "citrus-heights", "clayton", "clements", "colfax", "colma", "coloma", "concord", "cool", "crockett", "cupertino", "daly-city", "danville", "davis", "delhi", "denair", "diablo", "diamond-springs", "discovery-bay", "dixon", "dublin", "dunnigan", "east-palo-alto", "el-cerrito", "el-dorado-hills", "el-macero", "el-sobrante", "elk-grove", "elverta", "emerald-hills", "emeryville", "escalon", "esparto", "fair-oaks", "fairfield", "fiddletown", "folsom", "foresthill", "forestville", "foster-city", "fremont", "french-camp", "galt", "gilroy", "gold-river", "gold-run", "granite-bay", "grass-valley", "greenbrae", "greenwood", "grizzly-flats", "guerneville", "half-moon-bay", "hayward", "healdsburg", "herald", "hercules", "hilmar", "hughson", "ione", "keyes", "kings-beach", "lafayette", "lathrop", "lincoln", "linden", "livermore", "livingston", "lockeford", "lodi", "loomis", "los-altos", "los-gatos", "manteca", "martinez", "marysville", "mather", "mcclellan", "meadow-vista", "menlo-park", "merced", "millbrae", "milpitas", "modesto", "montara", "moraga", "morgan-hill", "mountain-house", "mountain-view", "napa", "nevada-city", "newark", "newcastle", "north-highlands", "north-sacramento", "novato", "oakdale", "oakland", "oakley", "olivehurst", "orangevale", "oregon-house", "orinda", "pacheco", "pacifica", "palo-alto", "penn-valley", "penryn", "petaluma", "piedmont", "pinole", "pittsburg", "placerville", "pleasant-grove", "pleasant-hill", "pleasanton", "plumas-lake", "plymouth", "portola-valley", "rancho-cordova", "redwood-city", "rescue", "richmond", "rio-linda", "rio-vista", "ripon", "riverbank", "rocklin", "rodeo", "rohnert-park", "roseville", "salida", "san-bruno", "san-carlos", "san-jose", "san-leandro", "san-lorenzo", "san-martin", "san-mateo", "san-pablo", "san-rafael", "san-ramon", "santa-clara", "santa-rosa", "saratoga", "sebastopol", "sheridan", "shingle-springs", "sloughhouse", "smartsville", "somerset", "sonoma", "south-lake-tahoe", "south-san-francisco", "stockton", "suisun-city", "sunnyvale", "tahoe-city", "thornton", "tracy", "truckee", "turlock", "union-city", "vacaville", "vallejo", "valley-springs", "w-sacramento", "walnut-creek", "walnut-grove", "waterford", "west-sacramento", "wheatland", "windsor", "winters", "winton", "woodbridge", "woodland", "woodside", "yuba-city", "san-francisco"
    ),
    'fresno' => array(
        "biola", "caruthers", "chowchilla", "clovis", "del-rey", "dos-palos", "fowler", "fresno", "kerman", "kingsburg", "laton", "los-banos", "merced", "orange-cove", "parlier", "pinedale", "raisin-city", "reedley", "riverdale", "sanger", "selma"
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

if (in_array($city, $cities['san-diego'])) {
    $links  = $cities['san-diego'];
    asort($links);
    $branch = 'San Diego';
    $state = 'California';
} else if (in_array($city, $cities['sacramento'])) {
    $links  = $cities['sacramento'];
    asort($links);
    $branch = 'Sacramento';
    $state = 'California';
} else if (in_array($city, $cities['fresno'])) {
    $links  = $cities['fresno'];
    asort($links);
    $branch = 'Fresno';
    $state = 'California';
} else {
    //$test = "none";
    null;
}

$otherAreas = implode(", ", array_map(function($item) {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $item_ref = ucwords(str_replace("-", " ", $item));
    //$item = $item . '-home-security';
    $item = $root . 'california/' . $item . '-home-security';
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