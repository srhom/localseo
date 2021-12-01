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
$start  = 'florida';
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
    'sarasota' => array(
        "alachua", "alva", "anna-maria", "apollo-beach", "arcadia", "astor", "auburndale", "avon-park", "babson-park", "bartow", "bayonet-point", "belleair", "belleair-beach", "belleair-bluffs", "belleview", "beverly-hills", "bokeelia", "bonita-springs", "braden-river", "bradenton", "bradenton--uninc", "bradenton-bch", "bradenton-beach", "bradenton-uninc", "brandon", "brooksville", "cape-coral", "cape-haze", "carrollwood", "chiefland", "citra", "citrus-springs", "clearwater", "clearwater-beach", "clermont", "clewiston", "copeland", "cortez", "crystal-beach", "crystal-river", "dade-city", "davenport", "dover", "duette", "dundee", "dunedin", "dunnellon", "eagle-lake", "ellenton", "englewood", "englewood-beach", "estero", "eustis", "everglades-city", "floral-city", "fort-meade", "fort-myers", "fort-myers-beach", "frostproof", "fruitland-park", "ft-myers", "ft-myers-beach", "gainesville", "gibsonton", "grand-island", "groveland", "gulfport", "haines-city", "hawthorne", "hernando", "high-springs", "holiday", "holmes-beach", "homosassa", "howey-in-the-hills", "hudson", "immokalee", "indian-rocks-beach", "indian-shores", "inverness", "kathleen", "kenneth-city", "kissimmee", "la-belle", "labelle", "lady-lake", "lake-alfred", "lake-hamilton", "lake-suzy", "lake-wales", "lakeland", "lakewood-ranch", "land-o-lakes", "largo", "laurel", "lecanto", "leesburg", "lehigh-acres", "lithia", "lk-panasoffke", "longboat-key", "lutz", "madeira-beach", "marco-island", "matlacha", "minneola", "mount-dora", "mulberry", "myakka-city", "n-fort-myers", "n-ft-myers", "naples", "new-port-richey", "new-pt-richey", "nokomis", "north-county", "north-fort-myers", "north-port", "north-redington-beach", "north-venice", "northport", "ocala", "ocklawaha", "odessa", "okahumpka", "oldsmar", "ona", "oneco", "osprey", "oxford", "palm-harbor", "palmetto", "parrish", "pasadena", "pinellas-park", "placida", "plant-city", "poinciana", "polk-city", "port-charlotte", "port-richey", "pt-charlotte", "punta-gorda", "reddick", "redington-shores", "ridge-manor", "riverview", "rotonda-west", "ruskin", "s-pasadena", "safety-harbor", "saint-petersburg", "san-antonio", "sanibel", "sebring", "seffner", "seminole", "shady-hills", "siesta-key", "silver-springs", "sorrento", "south-pasadena", "spring-hill", "st-james-city", "st-pete-beach", "st-petersburg", "summerfield", "sun-city-center", "tallevast", "tampa", "tarpon-springs", "tavares", "temple-terrace", "terra-ceia", "the-villages", "thonotosassa", "tierra-verde", "treasure-island", "trinity", "umatilla", "university-park", "valrico", "venice", "wauchula", "webster", "weeki-wachee", "weirsdale", "wesley-chapel", "wildwood", "wimauma", "winter-haven", "ybor-city", "zephyrhills", "zolfo-springs","lochmoor-waterway-estates","newberry","pine-manor"
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

if (in_array($city, $cities['sarasota'])) {
    $links  = $cities['sarasota'];
    asort($links);
    $branch = 'Sarasota';
    $state = 'Florida';
} else {
    //$test = "none";
    null;
}

$otherAreas = implode(", ", array_map(function($item) {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $item_ref = ucwords(str_replace("-", " ", $item));
    //$item = $item . '-home-security';
    $item = $root . 'florida/' . $item . '-home-security';
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