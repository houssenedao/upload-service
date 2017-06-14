# upload-service
Laravel service to upload, update and delete a file storage


## Polymorphic relation

``` PHP
Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('uploadable');
            $table->string('url');
            $table->string('type');
            $table->timestamps();
});
```

## Model Upload

```PHP
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'url', 'type', 'uploadable_id', 'uploadable_type',
    ];


    public function uploadable() {
        return $this->morphTo();
    }
}
```

## Model Article

```PHP
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{

    protected $fillable = [
        'title', 'summary', 'content', 'category_id', 'published',
    ];

    public function uploads() {
        return $this->morphMany(Upload::class, 'uploadable');
    }
```

## How to use


``` PHP
if ($request->hasFile('file'))
    UploadFiles::saveFile($request->file('file'), 'article', 'blog', $created);
```

## Example

### Store or created

```PHP
public function store(ArticleCreateRequest $request)
{
        // Create a article
        $created = $request->user()
            ->articles()
            ->create($request->all());
        // Query is ave
        if($created)
            // Upload Cover Blog file if exist
            if ($request->hasFile('cover'))
                UploadFiles::saveFile($request->file('cover'), 'article', 'blog', $created);
            // Redirect to back page
            return redirect()
                ->back()
                ->with('success', 'This content is stored successfully');
}
```

### update
```PHP
public function update(ArticleUpdateRequest $request, Article $article)
    {
        $update = $article->fill($request->all());

        if($update->save())
            if ($request->hasFile('cover'))
                UploadFiles::updateFile($request->file('cover'), 'blog', $article, $article->uploads()->first());

            return redirect()
                ->back()
                ->with('success', 'This content is updated successfully');
    }
```

### Delete

```PHP
public function destroy(Article $article)
    {

        $file = $article->uploads()->first();

        if($file)
            UploadFiles::deleteFile($file);

        if($article->delete())
            return response('This content is permanently deleted', 200);
    }


