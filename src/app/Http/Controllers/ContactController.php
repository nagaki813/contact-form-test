<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $contact = $request->only([
            'last_name',
            'first_name',
            'gender',
            'email',
            'tel1',
            'tel2',
            'tel3',
            'address',
            'building',
            'category_id',
            'detail',
        ]);

        $contact['tel'] =
            $request->tel1 . '-' .
            $request->tel2 . '-' .
            $request->tel3;

        $category = Category::find($request->category_id);

        return view('confirm', compact('contact', 'category'));
    }

    public function store(Request $request)
    {
        $tel = $request->tel1 .
               $request->tel2 .
               $request->tel3;

        Contact::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'tel' => $tel,
            'address' => $request->address,
            'building' => $request->building,
            'category_id' => $request->category_id,
            'detail' => $request->detail,
        ]);

        return redirect('/thanks');
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function back(Request $request)
    {
        return redirect('/')->withInput();
    }

    public function admin(Request $request)
    {
        $query = Contact::with('category');

        if(!empty($request->keyword)) {
            $keyword = trim($request->keyword);
            $keywordNoSpace = str_replace([' ', '  '], '', $keyword);

            $query->where(function($q)use($keyword, $keywordNoSpace) {
                $q->where('last_name', 'like', '%' . $keyword . '%')
                ->orWhere('first_name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ['%' . $keywordNoSpace . '%'])
                ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ['%' . $keyword . '%'])
                ->orWhereRaw("CONCAT(last_name, '  ', first_name) LIKE ?", ['%' . $keyword . '%']);
            });
        }

        if(!empty($request->gender) && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        if(!empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        if(!empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->paginate(7)->appends($request->query());
        $categories = Category::all();

        return view('admin', compact('contacts', 'categories'));
    }

    public function destroy(Request $request)
    {
        Contact::findOrFail($request->id)->delete();

        return redirect('/admin');
    }

    public function export(Request $request)
    {
        $query = Contact::with('category');

        if(!empty($request->keyword)) {
            $keyword = trim($request->keyword);
            $keywordNoSpace = str_replace([' ','  '], '', $keyword);

            $query->where(function ($q) use ($keyword, $keywordNoSpace) {
                $q->where('last_name', 'like', '%' . $keyword . '%')
                ->orWhere('first_name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ['%' . $keywordNoSpace . '%'])
                ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ['%' . $keyword . '%'])
                ->orWhereRaw("CONCAT(last_name, '  ', first_name) LIKE ?", ['%' . $keyword . '%']);
            });
        }

        if(!empty($request->gender) && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        if(!empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        if(!empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->get();

        $fileName = 'contacts.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use($contacts) {
            $stream = fopen('php://output', 'w');

            fwrite($stream, "\xEF\xBB\xBF");

            fputcsv($stream, [
                'お名前',
                '性別',
                'メールアドレス',
                '電話番号',
                '住所',
                '建物名',
                'お問い合わせ種類',
                'お問い合わせ内容',
            ]);

            foreach ($contacts as $contact) {
                if( $contact->gender == 1) {
                    $genderText = '男性';
                } elseif($contact->gender == 2) {
                    $genderText = '女性';
                } else {
                    $genderText = 'その他';
                }

                fputcsv($stream, [
                    $contact->last_name . ' ' . $contact->first_name,
                    $genderText,
                    $contact->email,
                    $contact->tel,
                    $contact->address,
                    $contact->building,
                    optional($contact->category)->content,
                    $contact->detail,
                ]);
            }

            fclose($stream);
        };

        return response()->stream($callback, 200, $headers);
    }
}


