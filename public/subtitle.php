<?php

function fixSubtitles($filePath) {
    $content = file_get_contents($filePath);

    $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');

    $replace_pairs = [
        'Ý' => 'İ',
        'Þ' => 'Ş',
        'ð' => 'ğ',
        'þ' => 'ş',
        'ý' => 'ı',
        'Ã‡' => 'Ç',
        'Ã¶' => 'ö',
        'Ã¼' => 'ü',
        'ÅŸ' => 'ş',
        'Ä±' => 'ı',
        'ÄŸ' => 'ğ',
        'Ã–' => 'Ö',
        'Ãœ' => 'Ü',
        'Åž' => 'Ş',
        'Ä°' => 'İ',
        'Ã§' => 'ç',
        'â€™' => '’',
        'â€œ' => '“',
        'â€�' => '”',
        'â€“' => '–',
        'â€”' => '—',
        'â€˜' => '‘',
    ];

    $fixedContent = strtr($content, $replace_pairs);

    return $fixedContent;
}

function cleanUploadsFolder($folder) {
    $files = glob($folder . '/*'); 
    foreach($files as $file) { 
        if(is_file($file)) {
            unlink($file);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $errors = [];
    $unsupported_files = [];
    $zip = new ZipArchive();
    $zipFile = $target_dir . "subtitle_fix.zip";
    
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        echo json_encode([
            "success" => false,
            "message" => "Could not create ZIP file."
        ]);
        exit;
    }

    foreach ($_FILES["file_upload"]["tmp_name"] as $key => $tmp_name) {
        $original_file_name = pathinfo($_FILES["file_upload"]["name"][$key], PATHINFO_FILENAME);
        $file_extension = pathinfo($_FILES["file_upload"]["name"][$key], PATHINFO_EXTENSION);
        
        if ($file_extension !== 'srt') {
            $unsupported_files[] = $_FILES["file_upload"]["name"][$key];
            continue;
        }

        $uploaded_file_name = $original_file_name . "_edited_by_grizzly.srt";

        $target_file = $target_dir . basename($_FILES["file_upload"]["name"][$key]);
        $processed_file = $target_dir . $uploaded_file_name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $content = file_get_contents($target_file);
            if ($content === false) {
                $errors[] = $_FILES["file_upload"]["name"][$key];
                continue;
            }
            
            $fixed_content = fixSubtitles($target_file);

            $save_success = file_put_contents($processed_file, $fixed_content);
            if ($save_success !== false) {
                $zip->addFile($processed_file, $uploaded_file_name);
            } else {
                $errors[] = $_FILES["file_upload"]["name"][$key];
            }
        } else {
            $errors[] = $_FILES["file_upload"]["name"][$key];
        }
    }

    $zip->close();

    if (!empty($unsupported_files)) {
        echo json_encode([
            "success" => false,
            "message" => "Format not supported for the following files: " . implode(", ", $unsupported_files)
        ]);
        cleanUploadsFolder($target_dir);
    } elseif (empty($errors)) {
        echo json_encode([
            "success" => true,
            "message" => "Process was successful.",
            "download_url" => $zipFile
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Errors occurred in the following files: " . implode(", ", $errors)
        ]);
        cleanUploadsFolder($target_dir); 
    }
}

if (isset($_GET['cleanup']) && $_GET['cleanup'] == 'true') {
    $target_dir = "uploads/";
    cleanUploadsFolder($target_dir);
}

if (isset($_GET['delete_file'])) {
    $target_dir = "uploads/";
    $file_to_delete = $target_dir . basename($_GET['delete_file']);
    if (is_file($file_to_delete) && unlink($file_to_delete)) {
        echo json_encode(["success" => true, "message" => "File deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "File could not be deleted."]);
    }
}
?>
