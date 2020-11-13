<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller
{
    /** 
    *ブログ一覧を表示する
    * @return view
    */
    public function showList()
    {
        $blogs = Blog::all();

        //dd($blogs); デバックをここで止める
        
        return view('list', ['blogs' => $blogs]);

    }

    /** 
    *ブログ詳細を表示する
    * @param int $id
    * @return view
    */
    public function showDetail($id)
    {
        $blog = Blog::find($id);
        //dd($blogs); デバックをここで止める

        if(is_null($blog))
        {
            \Session::flash('err_msg', 'データがありません。');
            return redirect(route('blogs'));
        }
        
        return view('detail', ['blog' => $blog]);

    }

     /** 
    *ブログ登録を表示する
    * 
    * @return view
    */
    public function showCreate()
    {
        return view('form');        
    }

     /** 
    *ブログを登録する
    * 
    * @return view
    */
    public function exeStore(BlogRequest $request)
    {
        //dd($request->all());
        //　ブログのデータ受け取り
        $inputs = $request->all();

        \DB::beginTransaction();
        try
        {
            // ブログ登録
            Blog::create($inputs);
            \DB::commit();
        } catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }
      

        \Session::flash('err_msg', 'ブログを登録しました。');
        return redirect(route('blogs'));   
    }

    /** 
    *ブログ編集フォームを表示する
    * @param int $id
    * @return view
    */
    public function showEdit($id)
    {
        $blog = Blog::find($id);
        //dd($blogs); デバックをここで止める

        if(is_null($blog))
        {
            \Session::flash('err_msg', 'データがありません。');
            return redirect(route('blogs'));
        }
        
        return view('edit', ['blog' => $blog]);

    }

    /** 
    *ブログを更新する
    * 
    * @return view
    */
    public function exeUpdate(BlogRequest $request)
    {
        //dd($request->all());
        //　ブログのデータ受け取り
        $inputs = $request->all();

        //dd($inputs);
        \DB::beginTransaction();
        try
        {
            // ブログ登録
            $blog = Blog::find($inputs['id']);
            $blog->fill([
                'title' => $inputs['title'],
                'content' => $inputs['content']
            ]);
            $blog->save();
            \DB::commit();
        } catch(\Throwable $e)
        {
            \DB::rollback();
            abort(500);
        }
      

        \Session::flash('err_msg', 'ブログを更新しました。');
        return redirect(route('blogs'));   
    }

    /** 
    *ブログ削除
    * @param int $id
    * @return view
    */
    public function exeDelete($id)
    {
        if(empty($id))
        {
            \Session::flash('err_msg', 'データがありません。');
            return redirect(route('blogs'));
        }

        try
        {
            // ブログ削除
            Blog::destroy($id);
        } catch(\Throwable $e)
        {
            abort(500);
        }
        
        \Session::flash('err_msg', '削除しました。');
        return redirect(route('blogs'));

    }


}
