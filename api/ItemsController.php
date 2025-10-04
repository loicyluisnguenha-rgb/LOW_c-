<?php
declare(strict_types=1);

require_once __DIR__.'/db.php';
require_once __DIR__.'/utils.php';

final class ItemsController {

  // GET /items
  public static function index(): void {
    $db = DB::pdo();
    $auth = require_auth($db);
    $st = $db->prepare("SELECT id, title, notes, created_at, updated_at
                        FROM items
                        WHERE owner_id=?
                        ORDER BY id DESC");
    $st->execute([$auth['user_id']]);
    $rows = $st->fetchAll();
    json_out(200, ['ok'=>true,'data'=>$rows]);
  }

  // POST /items
  public static function create(): void {
    $db = DB::pdo();
    $auth = require_auth($db);
    $in = json_input();
    $title = trim((string)($in['title'] ?? ''));
    $content = (string)($in['content'] ?? '');
    if (!$title) json_out(400, ['ok'=>false,'error'=>'title_required']);

    $db->prepare("INSERT INTO items(owner_id,title,notes) VALUES(?,?,?)")
       ->execute([$auth['user_id'],$title,$content]);
    json_out(201, ['ok'=>true]);
  }

  // PUT /items/{id}
  public static function update(int $id): void {
    $db = DB::pdo();
    $auth = require_auth($db);
    $in = json_input();
    $title = trim((string)($in['title'] ?? ''));
    $content = (string)($in['content'] ?? '');
    if (!$title) json_out(400, ['ok'=>false,'error'=>'title_required']);

    $st = $db->prepare("UPDATE items SET title=?, notes=? WHERE id=? AND owner_id=?");
    $st->execute([$title,$content,$id,$auth['user_id']]);
    json_out(200, ['ok'=>true]);
  }

  // DELETE /items/{id}
  public static function delete(int $id): void {
    $db = DB::pdo();
    $auth = require_auth($db);
    $db->prepare("DELETE FROM items WHERE id=? AND owner_id=?")
       ->execute([$id,$auth['user_id']]);
    json_out(200, ['ok'=>true]);
  }
}
