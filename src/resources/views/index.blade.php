@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

    <h2 class="page-title">Contact</h2>

    <form class="contact-form" action="/confirm" method="post">
        @csrf

        <div class="form-row">
            <label class="form-label">
                お名前
                <span class="required">*</span>
            </label>
            <div class="form-content">
                <div class="name-box">
                    <div class="input-error-box">
                        <input type="text" name="last_name" placeholder="例: 山田" value="{{ old('last_name') }}">
                        @error('last_name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="input-error-box">
                        <input type="text" name="first_name" placeholder="例: 太郎" value="{{ old('first_name') }}">
                        @error('first_name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
                性別
                <span class="required">*</span>
            </label>
            <div class="form-content">
                <div class="gender-box">
                    <label class="radio-label">
                        <input type="radio" name="gender" value="1" {{ old('gender', '1') == '1' ? 'checked' : '' }}>
                        <span>男性</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="2" {{ old('gender') == '2' ? 'checked' : '' }}>
                        <span>女性</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="3" {{ old('gender') == '3' ? 'checked' : '' }}>
                        <span>その他</span>
                    </label>
                    @error('gender')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
            メールアドレス<span class="required">*</span>
            </label>
            <div class="form-content">
                <input type="email" name="email" placeholder="例: test@example.com" value="{{ old('email') }}">
                @error('email')
                        <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
                電話番号
                <span class="required">*</span>
            </label>
            <div class="form-content">
                <div class="tel-box">
                    <input type="tel" name="tel1" placeholder="090" value="{{ old('tel1') }}">
                    <span>-</span>
                    <input type="tel" name="tel2" placeholder="1234" value="{{ old('tel2') }}">
                    <span>-</span>
                    <input type="tel" name="tel3" placeholder="5678" value="{{ old('tel3') }}">
                </div>
                @if($errors->has('tel1'))
                    <p class="form__error">{{ $errors->first('tel1') }}</p>
                @elseif($errors->has('tel2'))
                    <p class="form__error">{{ $errors->first('tel2') }}</p>
                @elseif($errors->has('tel3'))
                    <p class="form__error">{{ $errors->first('tel3') }}</p>
                @endif
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
                住所<span class="required">*</span>
            </label>
            <div class="form-content">
                <input type="text" name="address" placeholder="例: 東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}">
                @error('address')
                        <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
                建物名
            </label>
            <div class="form-content">
                <input type="text" name="building" placeholder="例: 千駄ヶ谷マンション101" value="{{ old('building') }}">
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
                お問い合わせの種類
                <span class="required">*</span>
            </label>
            <div class="form-content">
                <div class="select-wrap">
                    <select name="category_id">
                        <option value="" hidden>選択してください
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->content }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-row form-row-textarea">
            <label class="form-label">
                お問い合わせ内容
                <span class="required">*</span>
            </label>
            <div class="form-content">
                <textarea name="detail" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
                @error('detail')
                        <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-button">
            <button class="submit-btn" type="submit">確認画面</button>
        </div>
    </form>
@endsection