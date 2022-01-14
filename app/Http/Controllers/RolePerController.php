<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class RolePerController extends Controller
{
    public function addRole(request $request){
        $this->validate($request, [


            'name' => 'required',
        ]);

             Role::create(['name' => $request->name]);

             return response()->json([ 'message' => 'them role thanh cong']);
        // $permission = Permission::create(['name' => 'edit articles']);
    }

    public function addPermission(request $request){
        $this->validate($request, [


            'name' => 'required',
        ]);

        Permission::create(['name' => $request->name]);

             return response()->json([ 'message' => 'them Permission thanh cong']);
        // $permission = Permission::create(['name' => 'edit articles']);
    }

    public function role_add_per(request $request){
        $role=Role::find(1);
        $per=Permission::find(4);
        $role->givePermissionTo($per);
        return response()->json([ 'message' => 'success']);

    }

    public function model_add_per(request $request){
       $user=User::find(3);
      $user->assignRole(['employee']);
     //  $user->syncPermissions(['edit','delete','read','create']);
        return response()->json([ 'message' => 'success']);

    }

    public function delete($id)
    {
        if (auth()->user()->hasRole(['admin', 'employee'])) {
            $book = Book::find($id);
            $book->delete();
            return response()->json(['message' => ' success']);
        } else {
            return response()->json(['error' => 'you not permission']);
        }
    }
}
