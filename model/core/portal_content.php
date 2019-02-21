<?php

/*
 * Copyright (C) 2019 Joe Nilson <joenilson@gmail.com>
 *
 *  * This program is free software: you can redistribute it and/or modify
 *  * it under the terms of the GNU Lesser General Public License as
 *  * published by the Free Software Foundation, either version 3 of the
 *  * License, or (at your option) any later version.
 *  *
 *  * This program is distributed in the hope that it will be useful,
 *  * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See th * e
 *  * GNU Lesser General Public License for more details.
 *  *
 *  * You should have received a copy of the GNU Lesser General Public License
 *  * along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */
namespace FacturaScripts\model;
/**
 * Description of portal_content
 *
 * @author Joe Nilson <joenilson@gmail.com>
 */
class portal_content extends \fs_model
{
    public $id;
    public $page_name;
    public $content_type;
    public $content_content;
    public $content_fecha_inicio;
    public $content_fecha_fin;
    public $fecha_creacion;
    public $fecha_modificacion;
    public $usuario_creacion;
    public $usuario_modificacion;
    public $estado;
    
    public $array_tipos_contenido = array(
        array('id' => 'header_title', 'descripcion' => 'Título de Cabecera'),
        array('id' => 'header_text', 'descripcion' => 'Contenido de Cabecera'),
        array('id' => 'content_title', 'descripcion' => 'Título Central'),
        array('id' => 'content_text', 'descripcion' => 'Contenido Central'),
        array('id' => 'footer_title', 'descripcion' => 'Título de Pie de Página'),
        array('id' => 'footer_text', 'descripcion' => 'Contenido de Pie de Página')
    );
    
    public function __construct($t = false)
    {
        parent::__construct('portal_content', 'plugins/portal_clientes/');
        if ($t) {
            $this->id = $t['id'];
            $this->page_name = $t['page_name'];
            $this->content_type = $t['content_type'];
            $this->content_content = $t['content_content'];
            $this->content_fecha_inicio = $t['content_fecha_inicio'];
            $this->content_fecha_fin = $t['content_fecha_fin'];
            $this->fecha_creacion = $t['fecha_creacion'];
            $this->fecha_modificacion = $t['fecha_modificacion'];
            $this->usuario_creacion = $t['usuario_creacion'];
            $this->usuario_modificacion = $t['usuario_modificacion'];
            $this->estado = $this->str2bool($t['estado']);
        } else {
            $this->id = null;
            $this->page_name = null;
            $this->content_type = null;
            $this->content_content = null;
            $this->content_fecha_inicio = null;
            $this->content_fecha_fin = null;
            $this->fecha_creacion = null;
            $this->fecha_modificacion = null;
            $this->usuario_creacion = null;
            $this->usuario_modificacion = null;
            $this->estado = false;
        }
    }

    protected function install()
    {
        return "";
    }

    public function exists()
    {
        if (is_null($this->id)) {
            return false;
        } else {
            return $this->db->select("SELECT * FROM ".$this->table_name." WHERE id = ".$this->intval($this->id).";");
        }
    }

    public function save()
    {
        if ($this->exists()) {
            $sql = "UPDATE ".$this->table_name." SET ".
                    "page_name = ".$this->var2str($this->page_name).", ".
                    "content_type = ".$this->var2str($this->content_type).", ".
                    "content_content = ".$this->var2str($this->content_content).", ".
                    "content_fecha_inicio = ".$this->var2str($this->content_fecha_inicio).", ".
                    "content_fecha_fin = ".$this->var2str($this->content_fecha_fin).", ".
                    "usuario_modificacion = ".$this->var2str($this->usuario_modificacion).", ".
                    "fecha_modificacion = ".$this->var2str($this->fecha_modificacion).", ".
                    "estado = ".$this->var2str($this->estado)." WHERE id = ".$this->var2str($this->id).";";

            return $this->db->exec($sql);
        } else {
            $sql = "INSERT INTO ".$this->table_name.
                    " (page_name, content_type, content_content, content_fecha_inicio, content_fecha_fin, estado, usuario_creacion, fecha_creacion) ".
                    " VALUES ".
                    "(".
                    $this->var2str($this->page_name).", ".
                    $this->var2str($this->content_type).", ".
                    $this->var2str($this->content_content).", ".
                    $this->var2str($this->content_fecha_inicio).", ".
                    $this->var2str($this->content_fecha_fin).", ".
                    $this->var2str($this->estado).", ".
                    $this->var2str($this->usuario_creacion).", ".
                    $this->var2str($this->fecha_creacion).");";
            if ($this->db->exec($sql)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function delete()
    {
        return $this->db->exec("DELETE FROM ".$this->table_name." WHERE id = ".$this->var2str($this->id).";");
    }

    public function all()
    {
        $lista = array();
        $data = $this->db->select("SELECT * FROM ".$this->table_name." ORDER BY page_name,id");

        if ($data) {
            foreach ($data as $d) {
                $lista[] = new portal_content($d);
            }
        }

        return $lista;
    }
    
    public function all_activos()
    {
        $lista = array();
        $data = $this->db->select("SELECT * FROM ".$this->table_name." WHERE estado = TRUE ORDER BY page_name,id");

        if ($data) {
            foreach ($data as $d) {
                $lista[] = new portal_content($d);
            }
        }

        return $lista;
    }

    public function get($id)
    {
        $data = $this->db->select("SELECT * FROM ".$this->table_name." WHERE id = ".$this->intval($id).";");

        return new portal_content($data[0]);
    }
    
    public function get_page_content($page_name)
    {
        $data = $this->db->select("SELECT descripcion FROM ".$this->table_name." WHERE page_name = ".$this->var2str($page_name)." AND estado = TRUE;");
        $lista = array();
        if ($data) {
            foreach ($data as $d) {
                $item = new portal_content($d);
                $lista[] = $item;
            }
        }
        return $lista;
    }

}
