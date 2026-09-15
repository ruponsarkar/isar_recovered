@extends('layouts.app') @php($labels = ['manuscript' => 'Submit Manuscript', 'author' => 'Join as Author', 'reviewer' => 'Join as Reviewer', 'editor' => 'Join as Editor']) @section('content')<div class="container my-3">
        <article class="paper-card">
            <div class="section-title">{{ $labels[$type] }}</div>
            <div class="paper-body">
                <p><strong>You may also contact us at <a
                            href="mailto:isarpublishers@gmail.com">isarpublishers@gmail.com</a>.</strong></p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data"
                    action="{{ $type === 'manuscript' ? route('manuscript.submit') : route('join.' . $type . '.submit') }}">
                    @csrf @if ($type === 'manuscript')
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Process</label><select name="mode"
                                    class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option>First Track Process</option>
                                    <option>Normal Process</option>
                                </select></div>
                            <div class="col-md-6"><label class="form-label">Article Type</label><select name="article_type"
                                    class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option>Research article</option>
                                    <option>Review article</option>
                                    <option>Short Communication</option>
                                    <option>Case Report</option>
                                    <option>Letter to editor</option>
                                </select></div>
                            <div class="col-md-6"><label class="form-label">Journal</label><input name="journal"
                                    value="{{ old('journal') }}" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Manuscript Title</label><input name="title"
                                    value="{{ old('title') }}" class="form-control" required></div>
                    @endif
                    <div class="col-md-6">
                        <label
                            class="form-label">{{ $type === 'manuscript' ? 'Corresponding Author' : 'Full Name' }}</label><input
                            name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email"
                            value="{{ old('email') }}" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Country</label><input name="country"
                            value="{{ old('country') }}" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Affiliation</label><input name="affiliation"
                            value="{{ old('affiliation') }}" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Phone</label><input name="phone"
                            value="{{ old('phone') }}" class="form-control" required></div>
                    @if ($type !== 'manuscript')
                        <div class="col-md-6"><label class="form-label">Area of Expertise</label><input name="expertise"
                                value="{{ old('expertise') }}" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Publications</label><textarea name="publication"
                                class="form-control">{{ old('publication') }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Profile Photo <small>(JPG, JPEG,
                                    PNG)</small></label><input type="file" accept=".jpg,.jpeg,.png" name="photo"
                                class="form-control" required></div>
                    @endif
                    <div class="col-12">
                        <label class="form-label">{{ $type === 'manuscript' ? 'Article File' : 'CV / Supporting File' }}
                            <small>(PDF or DOCX)</small></label><input type="file" accept=".pdf,.docx" name="file"
                            class="form-control" required>
                    </div>
                    <div class="col-12 text-center"><button class="btn btn-isar px-4" type="submit">Submit
                            {{ $type === 'manuscript' ? 'Manuscript' : 'Application' }} <i
                                class="fa-solid fa-paper-plane"></i></button></div>
                    @if ($type === 'manuscript')
            </div>
            @endif
            </form>
    </div>
    </article>
</div>@endsection
