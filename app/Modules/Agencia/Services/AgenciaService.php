<?php

namespace Modules\Agencia\Services;

use App\Models\Agencia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AgenciaService
{
    public function all()
    {
        return Agencia::all();
    }

    public function find(int $id)
    {
        return Agencia::findOrFail($id);
    }

    public function create(array $data): Agencia
    {
        if (request()->hasFile('image')) {
            $data['imagenUrl'] = $this->uploadImage(request()->file('image'));
        }

        return Agencia::create($data)->fresh();
    }

    public function update(Agencia $agencia, array $data): Agencia
    {
        if (request()->hasFile('image') && request()->file('image')->isValid()) {
            // Eliminar imagen anterior si existe
            $this->deleteImage($agencia->imagenUrl);

            // Subir nueva imagen
            $data['imagenUrl'] = $this->uploadImage(request()->file('image'));
        }

        $agencia->update($data);

        return $agencia->fresh();
    }

    public function delete(Agencia $agencia): bool
    {
        $this->deleteImage($agencia->imagenUrl);
        return (bool) $agencia->delete();
    }

    /**
     * Sube la imagen al disco público en la carpeta 'agencias'.
     */
    private function uploadImage(UploadedFile $image): string
    {
        $folder = 'agencias';

        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        $filename = time() . '_' . $image->getClientOriginalName();
        return $image->storeAs($folder, $filename, 'public');
    }

    /**
     * Elimina una imagen del almacenamiento si existe.
     */
    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
