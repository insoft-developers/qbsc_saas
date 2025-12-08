<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        for($i=0; $i<25; $i++) {
            Notifikasi::create([
                "pengirim" => "user ".$i,
                "judul" => "Judul Notifikasi ".$i,
                "pesan" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae ante dapibus, malesuada neque id, semper justo. Integer gravida lacinia risus, eu dignissim dolor congue nec. Cras ac risus sed ipsum interdum efficitur. Nullam congue auctor erat, at vehicula libero elementum sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Maecenas vulputate luctus tortor nec tristique. Aenean scelerisque magna non magna gravida commodo. Nullam in risus posuere, feugiat leo quis, auctor lectus. Maecenas sodales sem in orci dapibus tristique. Nunc tincidunt, ante eu imperdiet sollicitudin, massa lorem lacinia elit, nec tempus neque justo non risus. Quisque suscipit libero eget orci gravida, id hendrerit sem lacinia. Vestibulum feugiat orci ut orci hendrerit vehicula. Integer congue justo vel ipsum congue sodales. Sed nec risus et nisl mattis fringilla ac ut justo. Vivamus sit amet augue commodo, fermentum augue vitae, dictum justo. Sed tristique a lacus sit amet commodo. Nullam tempor porta ultrices. Vestibulum posuere malesuada velit, vel volutpat erat ultricies nec. Integer nec risus lacus. Etiam nec eros mattis, cursus nunc nec, dictum sem. Sed auctor mi in augue hendrerit, et placerat ante luctus.",
                "is_read" => 0,
                "comid" => 1
            ]);
        }
    }
}
