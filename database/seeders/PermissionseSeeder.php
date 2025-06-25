<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use App\Models\User;

use Spatie\Permission\Models\Role;

class PermissionseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            'role-list',
 
            'role-create',
 
            'role-edit',
 
            'role-delete',
 
            'tache-list',
 
            'tache-create',
 
            'tache-edit',
 
            'tache-delete',
            'tache-show',

            'objectif-list',
 
            'objectif-create',
 
            'objectif-edit',
 
            'objectif-delete',
            'objectif-show',
            

            'formation-list',
 
            'formation-create',
 
            'formation-edit',
 
            'formation-delete',
            'formation-show',
            
            'project-list',
 
            'project-create',
 
            'project-edit',
 
            'project-delete',
            'project-show',


            'vente-list',
 
            'vente-create',
 
            'vente-edit',
 
            'vente-delete',
            'vente-show',

            'reclamation-list',
 
            'reclamation-create',
 
            'reclamation-edit',
 
            'reclamation-delete',
            'reclamation-show',


            'pointage-show',
            'pointage-list',

            'user-list',
 
            'user-create',
 
            'user-edit',
 
            'user-delete',
            
            'profile-edit',
 
            'profile-delete',
            'image_preuve-list',
 
            'image_preuve-create',
 
            'image_preuve-edit',
 
            'image_preuve-delete',
            'image_preuve-show'
 
         ];
 
       
 
         foreach ($permissions as $permission) {
 
              Permission::create(['name' => $permission]);
 
         }
         

         $user = User::create([

            'name' => 'Hardik Savani', 

            'email' => 'admin@gmail.com',

            'password' => bcrypt('123456')

        ]);

      

        $role = Role::create(['name' => 'Admin']);

       

        $permissions = Permission::pluck('id','id')->all();

     

        $role->syncPermissions($permissions);

       

        $user->assignRole([$role->id]);

    
    }
}
