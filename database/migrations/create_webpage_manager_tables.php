<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('type')->default('page');
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('set null');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->text('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('webpage_elements', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->text('code');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

          // Insert default webpage elements
          $this->insertDefaultWebpageElement('header');
          $this->insertDefaultWebpageElement('footer');
  
          // Insert default main page
          $this->insertDefaultMainPage();
  
          // Insert plugin settings
          $this->insertPluginSettings();
    }

    public function down()
    {
        Schema::dropIfExists('webpage_elements');
        Schema::dropIfExists('pages');
    }

    private function insertDefaultWebpageElement($type)
    {
        $templatePath = base_path("packages/startupful/webpage-manager/resources/views/templates/{$type}/default.php");
        $code = file_exists($templatePath) ? file_get_contents($templatePath) : "<!-- Default {$type} template not found -->";

        DB::table('webpage_elements')->insert([
            'type' => $type,
            'name' => 'default',
            'code' => $code,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertDefaultMainPage()
    {
        $content = '
        <div class="flex h-[40vh] items-center justify-center bg-white">
        <h1 class="text-center text-6xl font-bold text-white">
            <span class="bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent"> Hello, startupful! </span>
        </h1>
        </div>';

        DB::table('pages')->insert([
            'title' => 'main',
            'slug' => 'main',
            'content' => $content,
            'type' => 'page',
            'parent_id' => null,
            'is_published' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'meta_data' => null,
        ]);
    }

    private function insertPluginSettings()
    {
        $now = now();

        DB::table('plugin_settings')->insert([
            [
                'plugin_id' => 1,
                'plugin_name' => 'webpage-manager',
                'key' => 'theme',
                'value' => json_encode([
                    "app_logo" => null,
                    "container_width" => "1280px",
                    "primary_color" => "#000000",
                    "secondary_color" => "#ffffff"
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plugin_id' => 1,
                'plugin_name' => 'webpage-manager',
                'key' => 'menu',
                'value' => json_encode([
                    "unify_menus" => true,
                    "unified_menu" => [
                        ["label" => "About", "url" => "#", "target" => "_self", "children" => [
                            ["label" => "Company Overview", "url" => "#", "target" => "_self"],
                            ["label" => "History", "url" => "#", "target" => "_self"],
                            ["label" => "Vision & Mission", "url" => "#", "target" => "_self"],
                            ["label" => "Leadership", "url" => "#", "target" => "_self"],
                            ["label" => "CSR - Corporate Social Responsibility", "url" => "#", "target" => "_self"]
                        ]],
                        ["label" => "Services", "url" => "#", "target" => "_self", "children" => [
                            ["label" => "Service Overview", "url" => "#", "target" => "_self"],
                            ["label" => "Case Studies", "url" => "#", "target" => "_self"],
                            ["label" => "Customer Support", "url" => "#", "target" => "_self"]
                        ]],
                        ["label" => "Solutions", "url" => "#", "target" => "_self", "children" => [
                            ["label" => "Industry Solutions", "url" => "#", "target" => "_self"],
                            ["label" => "Custom Solutions", "url" => "#", "target" => "_self"],
                            ["label" => "Technical Support", "url" => "#", "target" => "_self"],
                            ["label" => "Partnerships", "url" => "#", "target" => "_self"]
                        ]],
                        ["label" => "Media", "url" => "#", "target" => "_self", "children" => [
                            ["label" => "Press Releases", "url" => "#", "target" => "_self"],
                            ["label" => "Media Gallery", "url" => "#", "target" => "_self"],
                            ["label" => "Blog", "url" => "#", "target" => "_self"],
                            ["label" => "Events", "url" => "#", "target" => "_self"],
                            ["label" => "Newsletter", "url" => "#", "target" => "_self"]
                        ]],
                        ["label" => "Careers", "url" => "#", "target" => "_self", "children" => [
                            ["label" => "Job Openings", "url" => "#", "target" => "_self"],
                            ["label" => "Our People", "url" => "#", "target" => "_self"],
                            ["label" => "Benefits", "url" => "#", "target" => "_self"],
                            ["label" => "Job Descriptions", "url" => "#", "target" => "_self"],
                            ["label" => "How to Apply", "url" => "#", "target" => "_self"]
                        ]],
                        ["label" => "Contact", "url" => "#", "target" => "_self", "children" => [
                            ["label" => "Contact Us", "url" => "#", "target" => "_self"],
                            ["label" => "Locations", "url" => "#", "target" => "_self"],
                            ["label" => "Customer Service", "url" => "#", "target" => "_self"],
                            ["label" => "Frequently Asked Questions", "url" => "#", "target" => "_self"]
                        ]]
                    ]
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
};