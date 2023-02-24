<div class="hidden-lg-up">
    <div class="search-block">
        <form action="{{ route('search.index') }}" method="POST">
            <div class="form-input-icon form-input-icon-right">
                <i class="icmn-search"></i>
                <input type="text" name="keyword" class="form-control form-control-sm form-control-rounded"
                    placeholder="Search...">
                @include('alert.feedback', ['field' => 'keyword'])
                <button type="submit" class="search-block-submit"></button>
            </div>
        </form>
    </div>
</div>
