<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type; 
use Illuminate\Support\Facades\DB; // 💡 Zidi Had Ligne

return new class extends Migration
{
    public function up()
{
    // 1. Zidi l'Type JSON f Doctrine (ila kan khassek)
    if (!Type::hasType('json')) {
        Type::addType('json', 'Doctrine\DBAL\Types\JsonType');
    }

    // 2. 💡 L'7al L'Muhim: Bédli ga3 les valeurs Strings l'JSON Array S7i7

    // A) Bédli les valeurs NULL wella '' l' '[]'
    DB::table('users')
        ->whereNull('repos')
        ->orWhere('repos', '')
        ->update(['repos' => '[]']);

    // B) Bédli les valeurs dyal String (b7al Lundi,Dimanche) l'JSON Array
    // N'hypothésw an l'strings dyalek kayn fihom ghir 'Lundi,Dimanche'
    // Had L'step hiya l'li kat7ell l'mochkil ila kén kayn chi String 3adi f l'database
    
    $usersWithOldRepos = DB::table('users')
        ->where('repos', 'not like', '[%') // Ma ytqissch li deja fih array
        ->get(['id', 'repos']);
    
    foreach ($usersWithOldRepos as $user) {
        $jours = explode(',', $user->repos);
        $jours = array_map('trim', $jours); // N7iyed l'espaces
        
        // N7awlo l'array l'JSON String
        $newReposJson = json_encode($jours); 
        
        // Update f l'database
        DB::table('users')->where('id', $user->id)->update(['repos' => $newReposJson]);
    }
    
    // 3. Modifi L'Type: Daba kat7awwel l'colonne l'JSON
    Schema::table('users', function (Blueprint $table) {
        $table->json('repos')->nullable()->change();
    });
}
};