import Link from "next/link";
import { Card, CardContent } from "@/components/ui/card";

type SearchResult = {
  id: string;
  title: string;
  publisher: string;
  imageUrl: string;
};

const results: SearchResult[] = [
  {
    id: "1",
    title: "Next.js Handbook",
    publisher: "TechBooks Publishing",
    imageUrl: "/file.svg",
  },
  {
    id: "2",
    title: "React for Beginners",
    publisher: "CodePress",
    imageUrl: "/file.svg",
  },
];

export default async function BookList({
  keyword,
  currentPage,
}: {
  keyword: string;
  currentPage: number;
}) {
  return (
    <div className="space-y-4 w-full">
      {results.map((result) => (
        <Link key={result.id} href={`/books/${result.id}`} className="block">
          <Card className="hover:shadow-md transition-shadow">
            <CardContent className="flex gap-6 p-4">
              <img
                src={result.imageUrl}
                alt={result.title}
                className="w-28 h-28 object-contain rounded"
              />
              <div className="flex flex-col justify-center">
                <h3 className="font-semibold text-lg">{result.title}</h3>
                <p className="text-sm text-muted-foreground">{result.publisher}</p>
              </div>
            </CardContent>
          </Card>
        </Link>
      ))}
    </div>
  );
}
