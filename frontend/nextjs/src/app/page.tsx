import BookList from "@/components/booklist";
import Search from "@/components/search";

export default async function HomePage(props: {
  searchParams: Promise<{
    keyword?: string;
    page?: string;
  }>;
}) {
  const searchParams = await props.searchParams;
  const keyword = searchParams?.keyword || "";
  const currentPage = Number(searchParams?.page) || 1;
  // const totalPages = await fetchBooksPages(keyword);

  return (
    <div className="flex flex-col items-center py-16 space-y-6">
      <h1 className="text-4xl font-bold">図書検索</h1>
      <Search />
      <BookList keyword={keyword} currentPage={currentPage} />
    </div>
  );
}
