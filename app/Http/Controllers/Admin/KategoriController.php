<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Kategori;
use App\Makale;
use App\Resim;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class KategoriController extends Controller
{
    //
    public function index()
    {
        $kategoriler = Kategori::paginate(10);

        return view("admin.kategori_index",compact('kategoriler'));

    }

    public function create()
    {
        return view("admin.kategori_create");
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "baslik" => "required|max:255",
            "resim" => "required"
        ]);

        $kategori = Kategori::create($request->all());

        if($resim = $request->file("resim"))
        {
            $resim_isim = time().".".$resim->getClientOriginalExtension();
            $thumb = "thumb_".time().".".$resim->getClientOriginalExtension();

            Image::make($resim->getRealPath())->fit(1900,872)->fill(array(0,0,0,0.5))->save(public_path("uploads/".$resim_isim));
            Image::make($resim->getRealPath())->fit(600,400)->save(public_path("uploads/".$thumb));

            $input = [];
            $input["isim"] = $resim_isim;
            $input["imageable_id"] =$kategori->id;
            $input["imageable_type"] ="App\Kategori";

        }

        Session::flash("durum",1);
        return redirect("/kategori");
    }

    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return view("admin.kategori_edit",compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            "baslik" => "required|max:255"

        ]);

        $kategori = Kategori::find($id);
        $kategori->update($request->all());

        if($resim = $request->file("resim"))
        {
            $resim_isim = $kategori->resim->isim;
            $thumb = "thumb_".$kategori->resim->isim;

            Image::make($resim->getRealPath())->fit(1900,872)->fill(array(0,0,0,0.5))->save(public_path("uploads/".$resim_isim));
            Image::make($resim->getRealPath())->fit(600,400)->save(public_path("uploads/".$thumb));

        }

        Session::flash("durum",1);
        return redirect("/kategori");
    }

    public function destroy($id)
    {
        $kategori_resim = Kategori::find($id)->resim->isim;

        unlink(public_path("uploads/".$kategori_resim));
        unlink(public_path("uploads/thumb_".$kategori_resim));

        Resim::where("imageable_id",$id)->where("imageable_type","App\Kategori")->delete();

        Kategori::destroy($id);

        Session::flash("durum",1);

        return redirect("/kategori");
    }

}
