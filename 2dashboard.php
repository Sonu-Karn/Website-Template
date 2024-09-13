<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all posts
$sql = "SELECT * FROM blog_posts";
$result = $conn->query($sql);

// Insert a new post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $author = $_POST["author"];

    // Check if an image was uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = $_FILES["image"]["name"];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Save the file to the target directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $title, $content, $author, $image);
                $stmt->execute();
                $stmt->close();

                // Redirect to avoid resubmission
                header('Location:  blog.html');
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        // Insert post without an image
        $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author);
        $stmt->execute();
        $stmt->close();

        // Redirect to avoid resubmission
        header('Location:  blog.html');
        exit();
    }
}
?>














<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: log.php'); // Redirect to login if not logged in
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];

// Fetch all posts
$sql = "SELECT * FROM blog_posts WHERE author = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fullname);
$stmt->execute();
$result = $stmt->get_result();

// Insert or Update a post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['post_id'])) {
        // Update existing post
        $post_id = $_POST['post_id'];
        $title = htmlspecialchars($_POST["title"]);
        $content = htmlspecialchars($_POST["content"]);

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $image = $_FILES["image"]["name"];
            $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $target_dir = "uploads/";
            $target_file = $target_dir . uniqid() . '.' . $imageFileType;

            if (in_array($imageFileType, $allowedTypes)) {
                if ($_FILES["image"]["size"] <= 2 * 1024 * 1024) {
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check !== false) {
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0755, true);
                        }

                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            // Update post with image
                            $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, content = ?, image = ? WHERE id = ? AND author = ?");
                            $stmt->bind_param("sssis", $title, $content, $target_file, $post_id, $fullname);
                            $stmt->execute();
                            $stmt->close();

                            header("Location: blog.php");
                            exit();
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    } else {
                        echo "File is not an image.";
                    }
                } else {
                    echo "File is too large. Maximum file size is 2MB.";
                }
            } else {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            // Update post without changing image
            $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, content = ? WHERE id = ? AND author = ?");
            $stmt->bind_param("ssis", $title, $content, $post_id, $fullname);
            $stmt->execute();
            $stmt->close();

            header("Location: blog.php");
            exit();
        }
    } else {
        // Insert new post
        $title = htmlspecialchars($_POST["title"]);
        $content = htmlspecialchars($_POST["content"]);

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $image = $_FILES["image"]["name"];
            $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $target_dir = "uploads/";
            $target_file = $target_dir . uniqid() . '.' . $imageFileType;

            if (in_array($imageFileType, $allowedTypes)) {
                if ($_FILES["image"]["size"] <= 2 * 1024 * 1024) {
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check !== false) {
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0755, true);
                        }

                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author, image) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssss", $title, $content, $fullname, $target_file);
                            $stmt->execute();
                            $stmt->close();

                            header("Location: blog.php");
                            exit();
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    } else {
                        echo "File is not an image.";
                    }
                } else {
                    echo "File is too large. Maximum file size is 2MB.";
                }
            } else {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $content, $fullname);
            $stmt->execute();
            $stmt->close();

            header("Location: blog.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
    <link rel="stylesheet" href="blog.css">
</head>
<body>
    <div class="blog-container">
        <h2 id="form-title">Create a New Blog Post</h2>
        <p>Welcome, <?php echo htmlspecialchars($fullname); ?>! <a href="logout.php">Logout</a></p>
        <form method="post" action="blog.php" enctype="multipart/form-data">
            <input type="hidden" name="post_id" id="post_id" value="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br>
            <button type="submit">Save</button>
        </form>
        <div id="error" class="error"></div>
    </div>

    <hr>

    <div class="blog-container">
        <h2>Existing Blog Posts</h2>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                if (!empty($row["image"])) {
                    echo "<img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["title"]) . "' style='max-width: 300px;'><br>";
                }
                echo "<p>" . htmlspecialchars($row["content"]) . "</p>";
                echo "<small>By " . htmlspecialchars($row["author"]) . " on " . htmlspecialchars($row["created_at"]) . "</small>";
                echo "<br><a href='#' class='edit-link' data-id='" . $row["id"] . "'>Edit</a>";
                echo "</div><hr>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let editLinks = document.querySelectorAll(".edit-link");
        editLinks.forEach(function(link) {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                let postId = link.getAttribute("data-id");
                document.getElementById("post_id").value = postId;

                // Fetch existing post data and populate the form for editing (optional)
                // Example: You can use AJAX to fetch the data and populate the form fields
                // Here, you can also redirect to a separate edit page if needed
            });
        });
    });
    </script>

</body>
</html>
