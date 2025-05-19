import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogFooter,
  DialogClose,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";

export default async function BookPage(props: { params: Promise<{ id: string }> }) {
  const { id } = await props.params;
  return (
    <div className="flex flex-col items-center py-16 space-y-6">
      <h1 className="text-4xl font-bold">図書詳細</h1>
      <div className="w-full max-w-2xl">
        <Card>
          <CardHeader className="flex flex-row items-center space-x-6">
            <div className="">
              <img
                src="/file.svg"
                alt="Book Cover"
                className="w-28 h-28 object-contain rounded"
              />
            </div>
            <div className="flex flex-col justify-center">
              <CardTitle className="text-2xl">Book Title</CardTitle>
              <CardDescription className="text-xl">Publisher</CardDescription>
            </div>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead className="">蔵書</TableHead>
                  <TableHead>貸出ステータス</TableHead>
                  <TableHead className=""></TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow>
                  <TableCell>1</TableCell>
                  <TableCell>貸出中</TableCell>
                  <TableCell className="text-right">
                    <Button variant="outline" size="sm" className="">
                      予約
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
              <TableBody>
                <TableRow>
                  <TableCell>2</TableCell>
                  <TableCell>予約中 (1人)</TableCell>
                  <TableCell className="text-right">
                    <Button variant="outline" size="sm" className="">
                      予約
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
              <TableBody>
                <TableRow>
                  <TableCell>3</TableCell>
                  <TableCell>貸出可</TableCell>
                  <TableCell className="text-right">
                    <Dialog>
                      <DialogTrigger asChild>
                        <Button variant="outline" size="sm" className="">
                          取り置き依頼
                        </Button>
                      </DialogTrigger>
                      <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                          <DialogTitle>取り置きを依頼しますか？</DialogTitle>
                          <DialogDescription></DialogDescription>
                        </DialogHeader>
                        <DialogFooter className="sm:justify-end">
                          <DialogClose asChild>
                            <Button type="button" variant="secondary">
                              キャンセル
                            </Button>
                          </DialogClose>
                          <Button type="submit">OK</Button>
                        </DialogFooter>
                      </DialogContent>
                    </Dialog>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
