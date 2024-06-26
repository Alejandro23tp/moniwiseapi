<?php
class Usuarios_ctrl
{
    public $M_Usuarios = null;
    public function __construct()
    {
        $this->M_Usuarios = new M_Usuarios();
    }




    public function registrarUsuario($f3)
    {
        $mensaje = "";
        $newId = 0;
        $retorno = 0;

        // Obtener los datos del usuario desde la solicitud POST
        $usuario_usuario = $f3->get('POST.usuario_usuario');
        $usuario_clave = $f3->get('POST.usuario_clave');
        $usuario_nombre = $f3->get('POST.usuario_nombre');
        $usuario_cedula = $f3->get('POST.usuario_cedula');
        $usuario_telefono = $f3->get('POST.usuario_telefono');
        $usuario_correo = $f3->get('POST.usuario_correo');
        $tipo_usuario_id = $f3->get('POST.tipo_usuario_id'); // Ajustar según tu formulario y lógica
        $usuario_estado = $f3->get('POST.usuario_estado'); // Por defecto activo
        $fecha_creacion = date('Y-m-d H:i:s'); // Fecha actual

        // Verificar si el usuario ya existe
        $this->M_Usuarios->load(['usuario=?', $usuario_usuario]);
        if ($this->M_Usuarios->loaded() > 0) {
            $mensaje = "El usuario con ese nombre de usuario ya existe";
            $retorno = 0;
        } else {
            // Insertar el nuevo usuario en la base de datos
            $this->M_Usuarios->set('nombre', $usuario_nombre);
            $this->M_Usuarios->set('cedula', $usuario_cedula);
            $this->M_Usuarios->set('telefono', $usuario_telefono);
            $this->M_Usuarios->set('correo', $usuario_correo);
            $this->M_Usuarios->set('usuario', $usuario_usuario);
            $this->M_Usuarios->set('contraseña', $usuario_clave,); // Almacenar la contraseña de forma segura
            $this->M_Usuarios->set('tipo_usuario_id', $tipo_usuario_id);
            $this->M_Usuarios->set('estado', $usuario_estado);
            $this->M_Usuarios->set('fecha_creacion', $fecha_creacion);
            $this->M_Usuarios->save();

            $mensaje = "Usuario registrado correctamente";
            $newId = $this->M_Usuarios->get('id'); // Obtener el ID del nuevo usuario registrado
            $retorno = 1;
        }

        // Devolver la respuesta en formato JSON
        echo json_encode([
            'mensaje' => $mensaje,
            'id' => $newId,
            'retorno' => $retorno
        ]);
    }

    //Editar usuario por su ID
    public function editarUsuarioPorId($f3)
    {
        $idUsuario = $f3->get('POST.usuario_id');
        $usuario_nombre = $f3->get('POST.usuario_nombre');
        $usuario_cedula = $f3->get('POST.usuario_cedula');
        $usuario_telefono = $f3->get('POST.usuario_telefono');
        $usuario_correo = $f3->get('POST.usuario_correo');
        $usuario_usuario = $f3->get('POST.usuario_usuario');
        $usuario_clave = $f3->get('POST.usuario_clave');

        // Cargar el usuario por su ID
        $this->M_Usuarios->load(['id=?', $idUsuario]);

        if ($this->M_Usuarios->loaded() > 0) {
            // Actualizar los datos del usuario
            $this->M_Usuarios->set('nombre', $usuario_nombre);
            $this->M_Usuarios->set('cedula', $usuario_cedula);
            $this->M_Usuarios->set('telefono', $usuario_telefono);
            $this->M_Usuarios->set('correo', $usuario_correo);
            $this->M_Usuarios->set('usuario', $usuario_usuario);
            $this->M_Usuarios->set('contraseña', $usuario_clave,); // Almacenar la contraseña de forma segura


            $this->M_Usuarios->save();

            $mensaje = "Usuario editado correctamente";
            $retorno = 1;
        } else {
            $mensaje = "El usuario no está registrado.";
            $retorno = 0;
        }

        // Devolver la respuesta en formato JSON
        echo json_encode([
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ]);
    }




    //Ingreso con usuario y clave
    public function login($f3)
    {
        $usuario = new M_Usuarios();
        $mensaje = "";
        $newId = 0;


        $usuario->load(['usuario=?', $f3->get('POST.usuario_usuario')]);
        if ($usuario->loaded() > 0) {
            $usuario->load(['contraseña=?', $f3->get('POST.usuario_clave')]);
            if ($usuario->loaded() > 0) {
                //verificar si esta activo
                if ($usuario->get('estado') == 1) {
                    $mensaje = "Se ha ingresado correctamente";
                    $newId = $usuario->get('id');
                    $retorno = 1;
                } else {
                    $mensaje = "El usuario no esta activo";
                    $retorno = 0;
                }
            } else {
                $mensaje = "Clave incorrecta";
                $retorno = 0;
            }
        } else {
            $mensaje = "El usuario no existe";
            $retorno = 0;
        }
        echo json_encode([
            'mensaje' => $mensaje,
            'id' => $newId,
            'usuario_nombre' => $usuario->get('nombre'),
            'usuario_id' => $usuario->get('id'),
            'usuario_activo' => $usuario->get('estado'),
            'tipo_usuario_id' => $usuario->get('tipo_usuario_id'),
            'retorno' => $retorno

        ]);
    }




    public function recuperarClave($f3)
    {
        $mensaje = "";
        $retorno = 0;
        $usuario_usuario = $f3->get('POST.usuario_usuario');
        $nueva_contrasena = $f3->get('POST.nueva_contrasena');

        // Verificar si el usuario existe
        $this->M_Usuarios->load(['usuario=?', $usuario_usuario]);
        if ($this->M_Usuarios->loaded() > 0) {
            // Actualizar la contraseña
            $this->M_Usuarios->set('contraseña', $nueva_contrasena);
            $this->M_Usuarios->save();

            $mensaje = "Contraseña actualizada correctamente.";
            $retorno = 1;
        } else {
            $mensaje = "El usuario no está registrado.";
            $retorno = 0;
        }

        echo json_encode([
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ]);
    }

    // Función para listar un usuario por su ID
    public function listarUsuarioPorId($f3)
    {
        $idUsuario = $f3->get('POST.usuario_id');

        // Cargar el usuario por su ID
        $this->M_Usuarios->load(['id=?', $idUsuario]);

        if ($this->M_Usuarios->loaded() > 0) {
            // Usuario encontrado, devolver la información
            $usuario = [
                'id' => $this->M_Usuarios->get('id'),
                'nombre' => $this->M_Usuarios->get('nombre'),
                'cedula' => $this->M_Usuarios->get('cedula'),
                'telefono' => $this->M_Usuarios->get('telefono'),
                'correo' => $this->M_Usuarios->get('correo'),
                'usuario' => $this->M_Usuarios->get('usuario'),
                'contraseña' => $this->M_Usuarios->get('contraseña'),
                'tipo_usuario_id' => $this->M_Usuarios->get('tipo_usuario_id'),
                'estado' => $this->M_Usuarios->get('estado'),
                'fecha_creacion' => $this->M_Usuarios->get('fecha_creacion')
            ];

            $mensaje = "Usuario encontrado.";
            $retorno = 1;
        } else {
            // Usuario no encontrado
            $usuario = null;
            $mensaje = "Usuario no encontrado.";
            $retorno = 0;
        }

        // Devolver la respuesta en formato JSON
        echo json_encode([
            'mensaje' => $mensaje,
            'usuario' => $usuario,
            'retorno' => $retorno
        ]);
    }


    //Ver Usuarios
    public function verUsuarios($f3)
    {

        $cadenaSql = "SELECT * FROM usuarios Where tipo_usuario_id = 1";

        // Ejecuta la consulta
        $items = $f3->DB->exec($cadenaSql);

        // Formatear la respuesta
        $response = [
            'cantidad' => count($items),
            'data' => $items
        ];

        // Devolver la respuesta en formato JSON
        echo json_encode($response);
    }


    public function cambiarEstadoUsuario($f3)
    {
        $idUsuario = $f3->get('POST.usuario_id');
        $nuevo_estado = $f3->get('POST.estado');

        // Cargar el usuario por su ID
        $this->M_Usuarios->load(['id=?', $idUsuario]);

        if ($this->M_Usuarios->loaded() > 0) {
            // Actualizar el estado del usuario
            $this->M_Usuarios->set('estado', $nuevo_estado);
            $this->M_Usuarios->save();

            $mensaje = "Estado del usuario actualizado correctamente.";
            $retorno = 1;
        } else {
            $mensaje = "Usuario no encontrado.";
            $retorno = 0;
        }

        // Devolver la respuesta en formato JSON
        echo json_encode([
            'mensaje' => $mensaje,
            'retorno' => $retorno
        ]);
    }
} //fin clase
