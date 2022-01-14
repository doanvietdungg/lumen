<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\book_employ;
use Illuminate\Support\Facades\DB;

class bookController extends Controller
{
    public function getBook()
    {
        if (auth()->user()->hasRole(['admin'])) {
            $book = Book::all();
            return response()->json($book);
        } else if (auth()->user()->hasRole(['employee'])) {
            $id_employee = auth()->user()->id;
            $book = DB::select('select books.* from books ,(select book_employs.id_book as id from book_employs where book_employs.id_employe=' . $id_employee . ') as A where books.id=A.id');
            return response()->json($book);
        }
    }

    public function create(request $request)
    {
        $this->validate($request, [

            'name' => 'required',

        ]);
        if (auth()->user()->hasRole(['admin'])) {

            $book = new Book;
            $book->name = $request->name;
            $book->save();

            return response()->json($book);
        } else {
            return response()->json(['message' => 'chi co admin moi co quyen them']);
        }
    }

    public function delete(request $request)
    {
        if (auth()->user()->hasRole(['admin', 'employee'])) {
            $book = Book::find($request->id);
            $book->delete();
            return response()->json(['message' => 'delete success']);
        } else {
            return response()->json(['error' => 'you not permission']);
        }
    }
    public function update(request $request, $id)
    {
        if (auth()->user()->hasRole('admin', 'employee')) {
            $book = Book::find($id);
            $this->validate($request, [

                'name' => 'required',

            ]);

            $book->name = $request->name;
            $book->save();

            return response()->json($book);
        }
    }
    public function edit($id)
    {
        if (auth()->user()->hasRole(['admin'])) {
            $book = Book::find($id);
            return response()->json($book);
        } else if (auth()->user()->hasRole(['employee'])) {
            $check = book_employ::where('id_book', $id)->count();

            if ($check == 0) {
                return response()->json(['error', 'ban ko co quyen xem sach nay']);
            } else {
                $book = Book::find($id);
                return response()->json($book);
            }
        }
    }
}
