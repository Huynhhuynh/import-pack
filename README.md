# Import Pack
Is a function help import package demo, backup site & restore.
Video demo [here](https://d.pr/free/v/M6U8Rr).

## Install via composer
```php
composer require huynhhuynh/import-pack
```

## Hooks
#### Package demos hook
```php
add_action( 'beplus/import_pack/package_demo', 'my_demo' );
function my_demo( $demos ) {
	return [
		[
			"package_name" => "main-demo",
			"preview" => "<image_url>", // image size 680x475
			"url_demo" => "<link_demo>",
			"title" => "Main Demo",
			"description" => "",
			"plugin" => [
				[
					"name" => esc_html__("Elementor", "text-domain"),
					'slug' => 'elementor'
				],
				[
					"name" => esc_html__("Slider Revolution", "text-domain"),
					"revslider" => "revslider",
					"source" => "https://<your_domain_plugin>/plugin/revslider.zip"
				]
			]
		]
	];
}
```