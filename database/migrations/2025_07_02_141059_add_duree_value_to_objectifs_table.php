    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('objectifs', function (Blueprint $table) {
                // Ajoute le champ duree_value
                $table->integer('duree_value')->nullable()->after('afaire'); // Vous pouvez ajuster 'after' si vous voulez une position spécifique
                // Ajoute le champ duree_type si ce n'est pas déjà fait
                $table->enum('duree_type', ['jours', 'semaines', 'mois', 'annee'])->nullable()->after('duree_value');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('objectifs', function (Blueprint $table) {
                // Supprime le champ duree_value si la migration est annulée
                $table->dropColumn('duree_value');
                // Supprime le champ duree_type si la migration est annulée
                $table->dropColumn('duree_type');
            });
        }
    };
    