<?php

use App\Models\{Comment, Course, Image, Lesson, Module, Permission, User, Preference, Tag};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/many-to-many-polymorphic', function () {
    // $course = Course::find(1);

    // // Tag::create(['name' => 'tag1', 'color' => 'blue']);
    // // Tag::create(['name' => 'tag2', 'color' => 'red']);
    // // Tag::create(['name' => 'tag3', 'color' => 'black']);

    // $course->tags()->attach([3]);

    // dd($course->tags);

    // $tag = Tag::find(1);
    $tag = Tag::where('name', 'tag2')->first();
    dd($tag->courses);
});

Route::get('/one-to-many-polymorphic', function () {
    // $course = Course::first();

    // $course->comments()->create([
    //     'subject' => 'Novo Comentário 2',
    //     'content' => 'Apenas 2 um comentário Legal',
    // ]);

    // dd($course->comments);

    // $lesson = Lesson::first();

    // $lesson->comments()->create([
    //     'subject' => 'Novo Comentário na lição',
    //     'content' => 'Apenas um comentário Legal',
    // ]);

    // dd($lesson->comments);

    //Como descobrir o tipo do comentario.
    $comment = Comment::find(1);
    dd($comment->commentable);

});

Route::get('/one-to-one-polymorphic', function () {
    $user = User::first();

    $data = [
        'path' => 'path/nome-da-imagem.png'
    ];

    // $user->image->delete();

    if ($user->image) {
        $user->image->update($data);
    } else {
        // $user->image()->save(new Image($data));
        $user->image()->create($data);
    }

    dd($user->image);
});

Route::get('/many-to-many-pivot', function () {
    $user = User::with('permissions')->find(1)->first();
    // $user->permissions()->attach([
    //     2 => ['active' => true],
    //     3 => ['active' => false],
    // ]);

    echo "<b> {$user->name} </b><br>";
    foreach ($user->permissions as $permission) {
        echo "- {$permission->name} | {$permission->pivot->active} <br>";
        // // consigo atualizar desta forma também, mas cuidado pois ele está realizando uma consulta a cada interação do loop.
        // // atribuindo algum valor ao nome da tabela pivot e depois salvando.
        // $permission->pivot->active = true;
        // $permission->pivot->save();
    }
    dd($user->permissions());
});

Route::get('/many-to-many', function () {
    $user = User::with('permissions')->find(1);

    $permission = Permission::find(1);
    // formas de salvar/atualizar as permissoes do usuario.
    // $user->permissions()->save($permission);
    $user->permissions()->saveMany([
        Permission::find(1),
        Permission::find(2),
        Permission::find(3),
    ]);
    // $user->permissions()->sync([2]);
    // $user->permissions()->attach([1, 3]);
    // $user->permissions()->detach([1, 3]);

    $user->refresh();

    dd($user->permissions);
});

Route::get('/one-to-many', function () {
    //para criar um curso;
    // $course = Course::create([
    //     'name' => 'Curso de Laravel 10'
    // ]);

    $course = Course::with('modules.lessons')->first();

    //para criar um modulo
    // $data = [
    //     'name' => 'Módulo x2'
    // ];

    // $course->modules()->create($data);


    // exibindo todos os registros
    echo $course->name . " id: " . $course->id;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo $module->name . " id: " . $module->id;
        echo '<br>';
        foreach ($module->lessons as $lesson) {
            echo $lesson->name . " id: " . $lesson->id;
            echo '<br>';
        }
    }

    $modules = $course->modules;

    dd($modules);

    //para criar uma lesson. Preciso recuperar o modulo. E a criação precisa ser feita atraves do modulo e não do curso.
    // $module = Module::with('lessons')->first();

    // // $data = [
    // //     'name' => 'Aula x2',
    // //     'video' => 'Video x2'
    // // ];

    // $module->lessons()->create($data);


    // $lessons = $module->lessons;


    // dd($lessons);
});

Route::get('/one-to-one', function () {
    // $user = User::with('preference')->find(1)->first();
    $user = User::with('preference')->find(1);

    $data = [
        'background_color' => '#fff',
    ];

    if ($user->preference) {
        $user->preference->update($data);
    } else {
        // $user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $user->refresh();

    // para remover:
    // $user->preference->delete();

    dd($user->preference);

});

Route::get('/', function () {
    return view('welcome');
});