export default class DeleteService 
{
  // 確認メッセージ
  static confirmAndDelete(element) {
      if(confirm('本当に削除していいですか？')) {
          document.getElementById('delete_' + element.dataset.id).submit();
      }
  }
}