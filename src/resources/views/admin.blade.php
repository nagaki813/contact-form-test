@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
    <div class="admin-page">
        <div class="admin-container">
            <h2 class="admin-title">Admin</h2>

            <form action="/admin" method="get" class="search-form">
                <input class="search-input keyword-input" type="text" name="keyword" placeholder="名前やメールアドレスを入力してください" value="{{ request('keyword') }}">

                <select class="search-select" name="gender">
                    <option value="">性別</option>
                    <option value="all" {{ request('gender') == 'all' ? 'selected' : '' }}>全て</option>
                    <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
                </select>

                <select class="search-select" name="category_id">
                    <option value="">お問い合わせの種類</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->content }}
                        </option>
                    @endforeach
                </select>

                <input class="search-input date-input" type="date" name="date" value="{{ request('date') }}">

                <button class="search-btn" type="submit">検索</button>
                <a class="reset-btn" href="/admin">リセット</a>
            </form>

            <div class="admin-toolbar">
                <a class="export-btn" href="{{ route('admin.export', request()->query()) }}">エクスポート</a>
            </div>

            <div class="pagination-wrapper">
                {{ $contacts->links() }}
            </div>

            <div class="table-wrapper">
                <table class="contact-table">
                    <thead>
                        <tr>
                            <th>名前</th>
                            <th>性別</th>
                            <th>メール</th>
                            <th>お問い合わせ内容</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
                                <td>
                                    @if($contact->gender == 1)
                                        男性
                                    @elseif($contact->gender == 2)
                                        女性
                                    @else
                                        その他
                                    @endif
                                </td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ Str::limit($contact->detail, 20, '...') }}</td>
                                <td class="detail-cell">
                                <label class="detail-btn" for="modal-{{ $contact->id }}">詳細</label>
                                </td>
                            </tr>

                            <input class="modal-toggle" type="checkbox" id="modal-{{ $contact->id }}">

                            <div class="modal">
                                <div class="modal-content">
                                    <label class="modal-close" for="modal-{{ $contact->id }}">×</label>

                                    <div class="modal-body">
                                        <div class="modal-row">
                                            <span class="modal-label">お名前</span>
                                            <span class="modal-value">{{ $contact->last_name }} {{ $contact->first_name }}</span>
                                        </div>
                                        <div class="modal-row">
                                            <span class="modal-label">性別</span>
                                            <span class="modal-value">
                                                @if($contact->gender == 1)
                                                    男性
                                                @elseif($contact->gender == 2)
                                                    女性
                                                @else
                                                    その他
                                                @endif
                                            </span>
                                        </div>
                                        <div class="modal-row">
                                            <span class="modal-label">メールアドレス</span>
                                            <span class="modal-value">{{ $contact->email }}</span>
                                        </div>
                                        <div class="modal-row">
                                            <span class="modal-label">電話番号</span>
                                            <span class="modal-value">{{ $contact->tel }}</span>
                                        </div>
                                        <div class="modal-row">
                                            <span class="modal-label">住所</span>
                                            <span class="modal-value">{{ $contact->address }}</span>
                                        </div>
                                        <div class="modal-row">
                                            <span class="modal-label">建物名</span>
                                            <span class="modal-value">{{ $contact->building }}</span>
                                        </div>
                                        <div class="modal-row">
                                            <span class="modal-label">お問い合わせの種類</span>
                                            <span class="modal-value">{{ optional($contact->category)->content }}</span>
                                        </div>
                                        <div class="modal-row textarea-row">
                                            <span class="modal-label">お問い合わせ内容</span>
                                            <span class="modal-value">{{ $contact->detail }}</span>
                                        </div>
                                    </div>
                                    <div class="modal-delete">
                                        <form class="delete-form" action="/admin/delete" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $contact->id }}">
                                        <button class="delete-btn" type="submit">削除</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection