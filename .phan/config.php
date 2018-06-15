<?php

return [
    'target_php_version' => 7.2,
    'directory_list' => [
        '.',
    ],
    'analyzed_file_extensions' => [
        "php",
        "js",
        "css",
    ],
    "exclude_analysis_directory_list" => [
        'src/vendor/'
    ],
    "exclude_file_list" => [
        "src/phpqrcode.php"
    ],
    "suppress_issue_types" => [
        "PhanUnreferencedPublicClassConstant",
        "PhanUnreferencedPublicMethod",
    ],
    "dead_code_detection" => true,
    "progress_bar" => true,
    "print_memory_usage_summary" => true,
];
