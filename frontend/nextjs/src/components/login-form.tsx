"use client";

import { cn } from "@/lib/utils";

import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useActionState } from "react";
import { useSearchParams } from "next/navigation";
import { authenticate } from "@/lib/actions";

import { loginFormSchema } from "@/auth";
import { useForm } from "@conform-to/react";
import { parseWithZod } from "@conform-to/zod";

export default function LoginForm({
  className,
  ...props
}: React.ComponentPropsWithoutRef<"div">) {
  const searchParams = useSearchParams();
  const callbackUrl = searchParams.get("callbackUrl") || "/";
  const [errorMessage, formAction, isPending] = useActionState(authenticate, undefined);

  const [form, fields] = useForm({
    onValidate({ formData }) {
      return parseWithZod(formData, { schema: loginFormSchema });
    },
    shouldValidate: "onBlur",
  });

  return (
    <div className={cn("flex flex-col gap-6", className)} {...props}>
      <Card>
        <CardHeader className="text-center">
          <CardTitle className="text-xl">ログイン</CardTitle>
        </CardHeader>
        <CardContent>
          <form
            id={form.id}
            onSubmit={form.onSubmit}
            noValidate
            action={formAction}
            className="grid gap-6"
          >
            <div className="grid gap-2">
              <Label htmlFor={fields.email.id}>Email</Label>
              <Input type="email" name={fields.email.name} placeholder="m@example.com" />
              <div className="text-red-500 text-sm">{fields.email.errors}</div>
            </div>
            <div className="grid gap-2">
              <Label htmlFor={fields.password.id}>パスワード</Label>
              <Input type="password" name={fields.password.name} />
              <div className="text-red-500 text-sm">{fields.password.errors}</div>
            </div>
            <input type="hidden" name="redirectTo" value={callbackUrl} />
            <Button type="submit" className="w-full" aria-disabled={isPending}>
              ログイン
            </Button>
            <div className="text-center text-sm text-red-500">
              {errorMessage && <p>{errorMessage}</p>}
            </div>
          </form>
          {/* </Form> */}
        </CardContent>
      </Card>
    </div>
  );
}
