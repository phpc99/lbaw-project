<?php

namespace App\Http\Controllers;

use App\Events\UserDeleted;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller 
{

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user')); 
    }

    public function profile() 
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'email' => 'email|max:255'
    ]);

    // Verifica se o email foi alterado
    $email_updated = $user->email != $request->input('email') ? true : false;

    if ($email_updated) {
        // Se o email foi alterado, valida todos os campos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:250',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'google_id' => 'nullable|string|max:255',
        ]);
    } else {
        // Se o email não foi alterado, valida apenas nome, telefone e foto
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
    }

    // Atualiza o nome
    $user->name = $validatedData['name'];

    // Se o email foi alterado, atualiza o email
    if ($email_updated) {
        $user->email = $validatedData['email'];
    }

    // Lida com o telefone, garantindo o formato +XXX XXXXXXXXX
    if ($request->has('ddd') && $request->has('phone_number')) {
        $phone_number = '+' . $request->input('ddd') . $request->input('phone_number');
        $user->phone_number = $phone_number;
    } else {
        $user->phone_number = $validatedData['phone_number'];  // Usa o valor do campo telefone como está se não tiver sido alterado
    }

    // Verifica se uma imagem foi enviada
    if ($request->hasFile('picture')) {
        // Diretório onde a imagem será salva
        $directory = "images/users";

        // Deletar a imagem antiga, se existir
        if ($user->picture) {
            $oldFilePath = public_path($user->picture); // Caminho completo
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Deletar o arquivo antigo
            }
        }

        // Salvar a nova imagem
        $file = $request->file('picture');

        // Gerar o nome do arquivo com o email do usuário
        $emailAsFilename = str_replace(['@', '.'], '_', strtolower($user->email)); // Substituir "@" e "." por "_"
        $extension = $file->getClientOriginalExtension(); // Obter a extensão do arquivo
        $filename = "{$emailAsFilename}.{$extension}"; // Nome final do arquivo

        $file->move(public_path($directory), $filename); // Salvar a imagem no diretório específico

        // Atualizar o caminho no banco de dados
        $user->picture = "{$directory}/{$filename}";
    }

    // Salva as alterações no banco de dados
    $user->save();

    // Redireciona de volta para o perfil do usuário com uma mensagem de sucesso
    return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
}

    public function destroy(Request $request) 
    {
        $user = Auth::user();

        if ($user) {
            event(new UserDeleted($user));
            
            Auth::logout();
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Your account has been deleted successfully.');
        }

        return redirect()->back()->with('error', 'Unable to delete account. If problem persists after trying multiple times, contact us.');
    }
    
}