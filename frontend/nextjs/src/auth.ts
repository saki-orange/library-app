import NextAuth from "next-auth";
import type { User } from "@/lib/definitions";
import { authConfig } from "./auth.config";
import Credentials from "next-auth/providers/credentials";
import { z } from "zod";
import bcrypt from "bcryptjs";

export const loginFormSchema = z.object({
  email: z.string().email(),
  password: z.string().min(6),
});

async function getUser(email: string): Promise<User | undefined> {
  try {
    // const user = await sql<User[]>`SELECT * FROM users WHERE email=${email}`;
    // モックデータ
    const user: User[] = [
      {
        id: "1",
        name: "John Doe",
        email: "test@gmail.com",
        password: "$2b$10$neU1RK9NTADm3mSz8ut49O.yu4.hOeYELTQ3qfTmCO4GA6o216e26",
      },
    ];
    return user[0];
  } catch (error) {
    console.error("Failed to fetch user:", error);
    throw new Error("Failed to fetch user.");
  }
}

export const { handlers, signIn, signOut, auth } = NextAuth({
  ...authConfig,
  providers: [
    Credentials({
      async authorize(credentials) {
        const parsedCredentials = loginFormSchema.safeParse(credentials);
        if (parsedCredentials.success) {
          const { email, password } = parsedCredentials.data;
          const user = await getUser(email);

          if (!user) return null;
          const passwordsMatch = await bcrypt.compare(password, user.password);

          if (passwordsMatch) return user;
        }

        return null;
      },
    }),
  ],
});
