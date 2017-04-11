# upload-service
Laravel service to upload, update and delete a file storage


## Plymorphic relation

``Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('uploadable');
            $table->string('url');
            $table->string('type');
            $table->timestamps();
        });


## How to use


``if ($request->hasFile('file'))
    UploadFiles::saveFile($request->file('file'), 'article', 'blog', $created);
