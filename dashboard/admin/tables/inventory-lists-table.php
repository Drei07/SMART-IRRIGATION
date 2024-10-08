<table class="table table-bordered table-hover">
<?php

require_once '../authentication/admin-class.php';
$itemId = isset($_SESSION['item_id']) ? $_SESSION['item_id'] : '';

$user = new ADMIN();
if(!$user->isUserLoggedIn())
{
    $user->redirect('../../../private/admin/');
}

// Use the runQuery method to prepare and execute queries.
function get_total_row($user, $itemId)
{
    $pdoQuery = "SELECT COUNT(*) as total_rows FROM products WHERE item_id = :item_id";
    $pdoResult = $user->runQuery($pdoQuery);
    $pdoResult->execute([':item_id' => $itemId]);
    $row = $pdoResult->fetch(PDO::FETCH_ASSOC);
    return $row['total_rows'];
}

$total_record = get_total_row($user, $itemId);
$limit = '20';
$page = 1;
if(isset($_POST['page']))
{
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
}
else
{
    $start = 0;
}

// Modify the query to fetch from products table
$query = "SELECT * FROM products WHERE item_id = :item_id AND status = :status";

$output = '';
if($_POST['query'] != '') {
    // Prepare the search term
    $search_term = $_POST['query'];
    $formatted_date = date("F j, Y", strtotime($search_term)); // Convert the search term to date format

    // Modify the query to search by name or description (assuming these columns exist in products table)
    $query .= ' AND product_code LIKE "%'.str_replace(' ', '%', $search_term).'%" 
                OR product_description LIKE "%'.str_replace(' ', '%', $search_term).'%" 
                OR DATE_FORMAT(created_at, "%M %e, %Y") LIKE "%'.str_replace(' ', '%', $formatted_date).'%"';
}

$query .= ' ORDER BY id DESC ';

// Prepare the query with LIMIT
$filter_query = $query . ' LIMIT '.$start.', '.$limit;

// Use the runQuery method to prepare and execute the query.
$statement = $user->runQuery($query);
$statement->execute([':item_id' => $itemId, ':status' => "available"]);
$total_data = $statement->rowCount();

// Use the runQuery method to prepare and execute the filtered query.
$statement = $user->runQuery($filter_query);
$statement->execute([':item_id' => $itemId, ':status' => "available"]);
$total_filter_data = $statement->rowCount();

if ($total_data > 0)
{
    $output = '
        <div class="row-count">
            Showing ' . ($start + 1) . ' to ' . min($start + $limit, $total_data) . ' of ' . $total_record . ' entries
        </div>
        <thead>
            <th># <i class="bx bx-sort"></i></th>
            <th>PRODUCT CODE <i class="bx bx-sort"></i></th>
            <th>PRODUCT DESCRIPTION <i class="bx bx-sort"></i></th>
            <th>QUANTITY IN STOCK <i class="bx bx-sort"></i></th>
            <th>DATE ADDED <i class="bx bx-sort"></i></th>
            <th>STOCK STATUS <i class="bx bx-sort"></i></th>

        </thead>
    ';

    while($row = $statement->fetch(PDO::FETCH_ASSOC))
    {
        // Set the appropriate button based on the product status
        if ($row["status"] == "available") {
            $button = '<button type="button" class="btn btn-danger V"><a href="controller/product-controller?id='.$row["id"].'&delete_product_lists=1" class="delete"><i class="bx bxs-trash"></i></a></button>';
        } else if ($row["status"] == "disabled") {
            $button = '<button type="button" class="btn btn-warning V"><a href="controller/product-controller?id='.$row["id"].'&activate_product_lists=1" class="activate">Activate</a></button>';
        }

        // Fetch stock details including created_at date
        $productId = $row['id'];
        $stockStmt = $user->runQuery("SELECT stock_quantity, created_at FROM product_inventory WHERE product_id = :product_id");
        $stockStmt->execute(array(":product_id" => $productId));
        $stock = $stockStmt->fetch(PDO::FETCH_ASSOC);

        $stockQuantity = $stock['stock_quantity'] ?? 0; // Fetch stock quantity or default to 0 if not found
        $dateAdded = isset($stock['created_at']) ? date("F j, Y", strtotime($stock['created_at'])) : 'N/A'; // Format created_at or default to 'N/A' if not found

        // Determine stock status and background color
        if ($stockQuantity > 20) {
            $stockStatus = '<span class="badge bg-success">In Stock</span>'; // Green
        } else if ($stockQuantity > 10) {
            $stockStatus = '<span class="badge bg-warning text-dark">Warning</span>'; // Orange
        } else {
            $stockStatus = '<span class="badge bg-danger">Out of Stock</span>'; // Red
        }

        $output .= '
        <tr>
            <td>'.$row["id"].'</td>
            <td>'.$row["product_code"].'</td>
            <td>'.$row["product_description"].'</td>
            <td>'.$stockQuantity.'</td>
            <td>'.$dateAdded.'</td>
            <td>
            '.$stockStatus.'
            </td>  
        </tr>
        ';
    }
}
else
{
    echo '<h1>No data found</h1>';
}


$output .= '</table>';
$output .= '<div align="center"><ul class="pagination">';

// Pagination logic (remains the same as your code)

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

if($total_links > 5)
{
    if($page < 5)
    {
        for($count = 1; $count <= 5; $count++)
        {
            $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
    }
    else
    {
        $end_limit = $total_links - 5;
        if($page > $end_limit)
        {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }
        else
        {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $page - 1; $count <= $page + 1; $count++)
            {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
}
else
{
    $page_array[] = '...';
    for($count = 1; $count <= $total_links; $count++)
    {
        $page_array[] = $count;
    }
}

for($count = 0; $count < count($page_array); $count++)
{
    if($page == $page_array[$count])
    {
        $page_link .= '
        <li class="page-item active">
            <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only"></span></a>
        </li>
        ';

        $previous_id = $page_array[$count] - 1;
        if($previous_id > 0)
        {
            $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
        }
        else
        {
            $previous_link = '
            <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
            </li>
            ';
        }
        $next_id = $page_array[$count] + 1;
        if($next_id > $total_links)
        {
            $next_link = '
            <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
            </li>
            ';
        }
        else
        {
            $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
        }
    }
    else
    {
        if($page_array[$count] == '...')
        {
            $page_link .= '
            <li class="page-item disabled">
                <a class="page-link" href="#">...</a>
            </li>
            ';
        }
        else
        {
            $page_link .= '
            <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
            ';
        }
    }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '</ul></div>';

echo $output;

?>
<script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
<script src="../../src/js/form.js"></script>
</table>
