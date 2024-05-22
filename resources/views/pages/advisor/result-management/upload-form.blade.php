<x-template nav="upload" main="results">
    <div class="h-full grid place-items-center">

        <form action="{{ route('import.results') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Choose Excel File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button type="submit" class="btn-primary">Import</button>
        </form>
    </div>
</x-template>
