<?php
return [
    "upload" => [
        "method"    => "POST",
        "params"    => [
            "content-type" => "string",
            "language" => "string",
            "filename" => "string",
            "user" => "string",
            "metadata" => "string",
            "detect-speaker-change" => "boolean",
        ]
    ],
    "export" => [
        "srt" => [
            "method"        => "POST",
            "returns_url"   => true,
            "params"        => [
                "exportAsParagraphs"        => "boolean",
                "exportSubtitleLength"      => "int",
                "exportHighlights"          => "boolean",
                "exportDisplaySpeakers"     => "boolean",
                "exportSpeakerPositionTop"  => "boolean",
                "exportUpperCaseSpeakers"   => "boolean",
                "exportSkipStrikethroughs"  => "boolean"
            ]
        ],
        "webvtt"    => [
            "method"        => "POST",
            "returns_url"   => true,
            "params"        =>  [
                "exportSubtitleLength"      =>  "boolean",
                "exportHighlights"          =>  "int",
                "exportDisplaySpeakers"     =>  "boolean",
                "exportSpeakerPositionTop"  =>  "boolean",
                "exportUpperCaseSpeakers"   =>  "boolean",
                "exportSkipStrikethroughs"  =>  "boolean",
            ]
        ],
        "edl" => [
            "method"        => "POST",
            "returns_url"   => true,
            "params"        =>  [
                "exportSkipStrikethroughs"  =>  "boolean",
            ]
        ],
        "docx" => [
            "method"        => "POST",
            "returns_url"   => true,
            "params"        =>  [
                "highlights"    =>  "boolean",
                "showTC"        =>  "boolean",
            ]
        ],
        "xml" => [
            "method"        => "POST",
            "returns_url"   => true,
            "params"        =>  []
        ],
        "json" => [
            "method"        => "POST",
            "returns_url"   => false,
            "params"        =>  []
        ]
    ]
];