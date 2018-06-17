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
        'src/vendor/',
    ],
    "suppress_issue_types" => [
        "PhanUnreferencedPublicClassConstant",
        "PhanUnreferencedPublicMethod",
        "PhanTypeMismatchDimFetch",
        "PhanTypeArraySuspiciousNullable",
    ],
    "dead_code_detection" => true,
    "progress_bar" => true,
    "print_memory_usage_summary" => true,
    "scalar_implicit_cast" => true,
];
